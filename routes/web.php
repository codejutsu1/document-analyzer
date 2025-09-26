<?php

use Inertia\Inertia;
use App\Jobs\ProcessDocumentJob;
use App\Jobs\ProcessUserQueryJob;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    
    Route::get('files', function () {
        return Inertia::render('File');
    })->name('file');

    Route::get('files/show', function () {
        return Inertia::render('Show');
    })->name('show');

    Route::get('files/chat', function () {
        return Inertia::render('Chat');
    })->name('chat');

});


Route::get('pdf', function () {
    // ProcessDocumentJob::dispatch('bill');
    ProcessUserQueryJob::dispatch('Is Benefit of kind part of pdf?');

    dd('live');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
