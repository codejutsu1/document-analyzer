<?php

namespace App\Http\Controllers;

use App\Models\File;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\File\FileShowResource;
use App\Http\Resources\Chat\ConversationResource;

class FileChatController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, File $file)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->load('conversations.messages');

        return Inertia::render('Chat', [
            'conversations' => fn () => ConversationResource::collection($user->conversations),
            'file' => fn () => new FileShowResource($file),
        ]);
    }
}
