<?php

namespace App\Http\Controllers;

use App\Http\Resources\Chat\ConversationResource;
use App\Http\Resources\Chat\MessageResource;
use App\Http\Resources\File\FileShowResource;
use App\Models\Conversation;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FileChatDetailsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, File $file, $conversationUuid)
    {
        $conversation = Conversation::with('messages')
            ->firstWhere('uuid', $conversationUuid);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        return Inertia::render('chats/Details', [
            'messages' => fn () => MessageResource::collection($conversation->messages),
            'file' => fn () => new FileShowResource($file),
            'conversation' => fn () => new ConversationResource($conversation),
            'conversations' => fn () => ConversationResource::collection($user->conversations),
        ]);
    }
}
