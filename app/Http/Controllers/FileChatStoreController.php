<?php

namespace App\Http\Controllers;

use App\Enums\MessageParticipant;
use App\Jobs\ProcessUserQueryJob;
use App\Models\Conversation;
use App\Models\File;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FileChatStoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, File $file, Conversation $conversation)
    {
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        try {
            $conversation = DB::transaction(function () use ($request, $conversation, $file) {
                if (empty($conversation->getAttributes())) {
                    $conversation = Conversation::create([
                        'user_id' => Auth::id(),
                        'file_id' => $file->id,
                    ]);
                }

                /** @var Message $message */
                $message = $conversation->messages()->create([
                    'user_id' => Auth::id(),
                    'message' => $request->message,
                    'participant' => MessageParticipant::USER,
                ]);

                ProcessUserQueryJob::dispatch($message, $file->path);

                return $conversation;
            });

            return redirect()->back()->with([
                'success', 'Message sent successfully',
                'data' => [
                    'conversation_uuid' => $conversation->uuid,
                ],
            ]);
        } catch (\Throwable $th) {
            report($th);

            return redirect()->back()->withErrors(['message' => 'Message sending failed']);
        }
    }
}
