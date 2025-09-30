<?php

use App\Models\File;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

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