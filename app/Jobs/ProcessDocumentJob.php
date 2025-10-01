<?php

namespace App\Jobs;

use App\Models\File;
use App\Enums\FileStatus;
use App\Services\Pdf\PdfService;
use App\Events\FilesStatusUpdated;
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
        protected File $file,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $pdfText = app(PdfService::class)->getPdfText($this->file->path);

        $chunks = $this->chuckText($pdfText, 1500, 500);

        Log::info('Processing chunk: '.$chunks[0]['chunk_index']);

        $this->file->chunking_status = FileStatus::COMPLETED;
        $this->file->embedding_status = FileStatus::ACTIVE;
        $this->file->save();

        Log::info('File event dispatched!');

        event(new FilesStatusUpdated($this->file));

        ProcessChunkJob::dispatch($chunks[0], $this->file);

        // foreach ($chunks as $chunk) {
        //     Log::info('Processing chunk: ' . $chunk['chunk_index']);

        //     ProcessChunkJob::dispatch($chunk, $this->file);
        // }

        // $this->file->status = FileStatus::COMPLETED;
        // $this->file->save();

    }

    protected function chuckText(
        string $text,
        int $chunkSize = 3000,
        int $overlap = 500
    ): array {
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
                'char_end' => $end,
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
}
