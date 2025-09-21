<?php

namespace App\Jobs;

use App\Services\Pdf\PdfService;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessDocumentJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected string $filename, 
        protected ?string $docId = null,
    )
    {

        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $pdfText = app(PdfService::class)->getPdfText($this->filename);

        $chunks = $this->chuckText($pdfText, 1500, 500);

        foreach ($chunks as $chunk) {
            Log::info('Processing chunk: ' . $chunk['chunk_index']);
            // $this->processAndStoreChunk($chunk);
        }
        
    } 
    
    protected function chuckText(string $text, int $chunkSize = 3000, int $overlap = 500): array
    {
        $len = mb_strlen($text);     

        $start = 0;
        $index = 0;

        while ($start < $len) { 
            $end = min($start + $chunkSize, $len);
            $chunk = mb_substr($text, $start, $end - $start);

            $chunks[] = [
                'text' => trim($chunk),
                'chunk_index' => $index,
                'char_start' => $start,
                'char_end' => $end
            ];

            $index++;

            if ($end >= $len) {
                break;
            }

            $start = $end - $overlap;
            if ($start < 0) $start = 0;
        }
    
        return $chunks;
    }

    protected function processAndStoreChunk(array $chunk): void
    {
        // foreach ($batches as $batch) {
        //     $texts = array_map(fn($c) => $c['text'], $batch);
        //     // $embeddings = $this->embedTexts($texts); // call embedding provider

        //     foreach ($batch as $i => $c) {
        //         $record = [
        //             'id' => $this->docId . '_chunk_' . $c['chunk_index'],
        //             'vector' => $embeddings[$i],
        //             'metadata' => [
        //                 'doc_id' => $this->docId,
        //                 'chunk_index' => $c['chunk_index'],
        //                 'char_start' => $c['char_start'],
        //                 'char_end' => $c['char_end'],
        //                 'page' => $c['page'] ?? null,
        //                 'filename' => $this->filename,
        //             ],
        //             'text' => $c['text'] // optionally store plain text
        //         ];

        //         $this->upsertVectorDb($record);
        //     }
        // }
    }
}
