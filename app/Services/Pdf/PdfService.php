<?php

namespace App\Services\Pdf;

use Spatie\PdfToText\Pdf;
use Smalot\PdfParser\Parser;
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

    public function getPdfText(string $filename): string
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($this->getPdf($filename));


        return $pdf->getText();
    }

    // public function chuckPdfToText(
    //     string $filename,
    //     int $chunkSize = 3000,
    //     int $overlap = 500,
    // ): array
    // {
    //     
    // }
}
