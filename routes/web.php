<?php

use Inertia\Inertia;
use App\Services\Pdf\PdfService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('pdf', function () {
    $text = app(PdfService::class)->convertPdfToText("testing");
    dd($text);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
