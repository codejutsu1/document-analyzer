<?php

use App\Models\File;
use Inertia\Inertia;
use App\Enums\FileStatus;
use App\Jobs\ProcessDocumentJob;
use App\Jobs\ProcessUserQueryJob;
use App\Events\FilesStatusUpdated;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Route::get('files', function () {
    //     return Inertia::render('File');
    // })->name('file');

    Route::get('files/show', function () {
        return Inertia::render('Show');
    })->name('show');

    // Route::get('files/chat', function () {
    //     return Inertia::render('Chat');
    // })->name('chat');

    Route::resource('files', FileController::class);

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

    $file->storage_status = FileStatus::COMPLETED;
    $file->save();
    event(new FilesStatusUpdated(File::find(1)));

    dd('live');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
