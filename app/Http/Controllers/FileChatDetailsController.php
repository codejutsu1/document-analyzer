<?php

namespace App\Http\Controllers;

use App\Models\File;
use Inertia\Inertia;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Http\Resources\Chat\MessageResource;
use App\Http\Resources\File\FileShowResource;
use App\Http\Resources\Chat\ConversationResource;

class FileChatDetailsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, File $file, $conversationUuid)
    {
        $conversation = Conversation::with('messages')
                                    ->firstWhere('uuid', $conversationUuid);

        return Inertia::render('ChatDetails', [
            'messages' => fn () => MessageResource::collection($conversation->messages),
            'file' => fn () => new FileShowResource($file),
            'conversation' => fn () => new ConversationResource($conversation),
        ]);
    }
}
