<?php

use App\Facades\Llm;
use Inertia\Inertia;
use Smalot\PdfParser\Parser;
use App\Jobs\ProcessDocumentJob;
use App\Services\Pdf\PdfService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('pdf', function () {
    ProcessDocumentJob::dispatch('bill');
    
    dd("live");
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
