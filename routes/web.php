<?php

use Inertia\Inertia;
use App\Jobs\ProcessDocumentJob;
use App\Jobs\ProcessUserQueryJob;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('files', function () {
    return Inertia::render('File');
})->middleware(['auth', 'verified'])->name('file');

Route::get('pdf', function () {
    // ProcessDocumentJob::dispatch('bill');
    ProcessUserQueryJob::dispatch('Is Benefit of kind part of pdf?');

    dd('live');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
