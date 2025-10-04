<?php

use App\Models\File;
use App\Models\Conversation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('file-status.{id}', function ($user, $id) {
    Log::info('file-status.' . $id);

    $fileExists = File::where('id', $id)
        ->where('user_id', $user->id)
        ->exists();

    Log::info('fileExists: ' . $fileExists);

    return $fileExists;
});

Broadcast::channel('user-files.{userId}', function ($user, $userId) {
    Log::info('user-files.' . $userId);

    return (int) $user->id === (int) $userId;
});

Broadcast::channel('message-created.{conversationId}', function ($user, $conversationId) {
    Log::info('message-created.' . $conversationId);

    $conversationExists = Conversation::where([
        'id' => $conversationId,
        'user_id' => $user->id,
    ])->exists();

    return $conversationExists;
});
