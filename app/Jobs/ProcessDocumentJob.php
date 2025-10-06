<?php

namespace App\Jobs;

use App\Models\File;
use App\Enums\FileStatus;
use App\Services\Pdf\PdfService;
use App\Events\FilesStatusUpdated;
use Illuminate\Support\Facades\Bus;
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

        $chuckCount = count($chunks);
       
        $jobs = [];

        foreach ($chunks as $chunk) {
            $jobs[] = new ProcessChunkJob($chunk, $this->file);
        }

        $jobs[] = new FinalizeFileJob($this->file);

        Log::info("Started processing $chuckCount chunks");

        Bus::chain($jobs)->dispatch();
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
