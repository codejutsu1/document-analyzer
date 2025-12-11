<?php

use App\Http\Controllers\FileChatController;
use App\Http\Controllers\FileChatDetailsController;
use App\Http\Controllers\FileChatStoreController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('files', FileController::class)->only(['index', 'show', 'store']);

    Route::get('files/{file}/chats', FileChatController::class)->name('chat');
    Route::post('/files/{file:uuid}/chats/{conversation:uuid?}', FileChatStoreController::class)->name('chat.store');

    Route::get('files/{file:uuid}/c/{conversation:uuid}', FileChatDetailsController::class)->name('chat.details');

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
