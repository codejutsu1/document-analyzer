<?php

namespace App\Services\Pdf;

use Spatie\PdfToText\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function savePdf(string $pdf): string
    {
        $pdf = Storage::disk('public')->put('pdfs', $pdf);

        return $pdf;
    }

    public function getPdf(string $filename): string
    {
        $pdf = str_ends_with($filename, '.pdf') 
                    ? $filename 
                    : $filename . '.pdf';

        return Storage::disk('public')->path('pdfs/' . $pdf);
    }

    public function convertPdfToText(string $filename): string
    {
        $text = Pdf::getText($this->getPdf($filename), null, ['l 3']);

        return $text;
    }
}
