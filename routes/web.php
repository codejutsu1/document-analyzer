<?php

use App\Models\File;
use Inertia\Inertia;
use App\Enums\FileStatus;
use App\Jobs\ProcessDocumentJob;
use App\Jobs\ProcessUserQueryJob;
use App\Events\FileDetailsUpdated;
use App\Events\FilesStatusUpdated;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FileChatController;
use App\Http\Controllers\FileChatStoreController;
use App\Http\Controllers\FileChatDetailsController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('files', FileController::class);

    Route::get('files/{file}/chats', FileChatController::class)->name('chat');
    Route::post('/files/{file:uuid}/chats/{conversation:uuid?}', FileChatStoreController::class)->name('chat.store');
    
    Route::get('files/{file:uuid}/c/{conversation:uuid}', FileChatDetailsController::class)->name('chat.details');

});

Route::get('pdf', function () {
    // ProcessDocumentJob::dispatch('bill');
    // ProcessUserQueryJob::dispatch('Is Benefit of kind part of pdf?');

    // FilesStatusUpdated::dispatch(File::find(1));
    $file = File::find(1);

    // $file->chunking_status = FileStatus::COMPLETED;
    // $file->embedding_status = FileStatus::ACTIVE;

    // $file->embedding_status = FileStatus::COMPLETED;
    // $file->storage_status = FileStatus::ACTIVE;   

    // $file->storage_status = FileStatus::COMPLETED;
    // $file->save();
    // event(new FilesStatusUpdated(File::find(1)));

    event(new FileDetailsUpdated(File::find(1)));

    dd('live');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
