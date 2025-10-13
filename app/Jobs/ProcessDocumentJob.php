<?php

namespace App\Jobs;

use App\Models\File;
use App\Services\Pdf\PdfService;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessDocumentJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected int $fileId,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $file = File::findOrFail($this->fileId);

        $fileId = $file->id;

        $pdfText = app(PdfService::class)->getPdfText($file->path);

        $chunks = $this->chuckText($pdfText, 1500, 500);

        $chuckCount = count($chunks);

        $jobs = [];

        // $chunks = array_slice($chunks, 0, 2);

        // $this->file->total_chunks = $chuckCount;
        /* @phpstan-ignore-next-line */
        $file->total_chunks = $chuckCount;
        $file->save();

        Log::info("Started processing $chuckCount chunks");

        $jobs = array_map(
            fn ($chunk) => new ProcessChunkJob($chunk, $file->id),
            $chunks
        );

        Bus::batch($jobs)
            ->then(function (Batch $batch) use ($fileId) {
                FinalizeFileJob::dispatch($fileId);
            })
            ->catch(function (Batch $batch, Throwable $e) use ($fileId) {
                Log::error("Batch failed for file_id={$fileId}: ".$e->getMessage());
            })
            ->finally(function (Batch $batch) use ($fileId) {
                Log::info("Batch finished for file_id={$fileId}. processed={$batch->processedJobs()} failed={$batch->failedJobs}");
            })
            ->dispatch();
    }

    protected function chuckText(
        string $text,
        int $chunkSize = 3000,
        int $overlap = 500
    ): array {
        $chunks = [];
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

    protected function sequenceJobs(): void
    {
        // foreach ($chunks as $chunk) {
        //     $jobs[] = new ProcessChunkJob($chunk, $this->file);
        // }

        // $jobs[] = new FinalizeFileJob($this->file);

        // $this->file->total_chunks = $chuckCount;
        // $this->file->total_chunks = 2;
        // $this->file->save();

        // Bus::chain($jobs)->dispatch();
    }
}
