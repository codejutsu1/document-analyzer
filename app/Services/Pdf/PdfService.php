<?php

namespace App\Services\Pdf;

use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

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
                    : $filename.'.pdf';

        return Storage::disk('public')->path($pdf);
    }

    public function getPdfText(string $filename): array
    {
        $parser = new Parser;
        $pdf = $parser->parseFile($this->getPdf($filename));

        $pages = [];
        foreach ($pdf->getPages() as $pageNumber => $page) {
            $pages[] = [
                'page' => $pageNumber + 1, // human-readable page number
                'text' => $page->getText(),
            ];
        }

        return $pages;
    }

    public function chunkText(
        array $documentText,
        int $chunkSize = 6000,
        int $overlap = 500
    ): array {
        $fullText = '';
        $pageMap = [];
        $currentPosition = 0;

        foreach ($documentText as $data) {
            $pageMap[] = [
                'start' => $currentPosition,
                'page' => $data['page'],
            ];

            $fullText .= $data['text'];

            $currentPosition += mb_strlen($data['text']);
        }

        $pageMap[] = ['start' => $currentPosition, 'page' => null];

        $chunks = [];
        $len = mb_strlen($fullText);

        $start = 0;
        $index = 0;

        while ($start < $len) {
            $end = min($start + $chunkSize, $len);
            $chunk = mb_substr($fullText, $start, $end - $start);

            $pagesSpanned = [];
            $firstPage = null;

            for ($i = 0; $i < count($pageMap) - 1; $i++) {
                $pageStart = $pageMap[$i]['start'];
                $nextPageStart = $pageMap[$i + 1]['start'];
                $pageNumber = $pageMap[$i]['page'];

                if ($start < $nextPageStart && $end > $pageStart) {
                    if ($firstPage === null) {
                        $firstPage = $pageNumber;
                    }
                    $pagesSpanned[] = $pageNumber;
                }
            }

            $chunks[] = [
                'text' => trim($chunk),
                'chunk_index' => $index,
                'char_start' => $start,
                'char_end' => $end,
                'page' => $firstPage,
                'pages_spanned' => array_unique($pagesSpanned),
            ];

            $index++;

            if ($end >= $len) {
                break;
            }

            $start = $end - $overlap;
            if ($start < 0) {
                $start = 0;
            }
        }

        return $chunks;
    }

    public function deletePdf(string $filePath): bool
    {
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->delete($filePath);
        }

        return false;
    }

    public function deleteAllFiles(): void
    {
        Storage::disk('public')->deleteDirectory('files');
        Storage::disk('public')->makeDirectory('files');
    }
}
