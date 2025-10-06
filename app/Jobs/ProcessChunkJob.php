<?php

namespace App\Jobs;

use App\Facades\Llm;
use App\Models\File;
use App\Enums\FileStatus;
use App\Events\FileProgressUpdated;
use Illuminate\Support\Str;
use App\Facades\VectorDatabase;
use App\Events\FilesStatusUpdated;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Middleware\RateLimited;
use App\Services\VectorDatabase\Data\QdrantUpsertPayload;

class ProcessChunkJob implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $backoff = 10;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public array $chunk,
        public File $file
    ) {}

    public function middleware(): array
    {
        return [
            new RateLimited('pdf-processing'),
        ];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $embedding = Llm::embed($this->chunk['text']);

        $uuid = Str::uuid();

        $payload = QdrantUpsertPayload::from([
            // 'id' => $this->filename . '_chunk_' . $this->chunk['chunk_index'],
            'id' => $uuid,
            'vector' => $embedding,
            'payload' => [
                'doc_id' => $this->file->path,
                'page' => $this->chunk['page'] ?? null,
                'chunk_index' => $this->chunk['chunk_index'],
                'text' => $this->chunk['text'],
            ],
        ]);

        Log::info($uuid);

        Log::info('Sending chunk '.$this->chunk['chunk_index'].' to vector database');

        VectorDatabase::upsert($payload);

        Log::info('Chunk '.$this->chunk['chunk_index'].' stored in vector database');

        Log::info('Chunk '.$this->chunk['chunk_index'].' processed successfully');

        $this->file->processed_chunks = $this->file->processed_chunks + 1;
        $this->file->save();

        event(new FilesStatusUpdated($this->file));

            // '595c678e-b6b3-4dac-8a51-b316cf03a50a';
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Chunk failed: '.$exception->getMessage());
        

        $this->file->status = FileStatus::FAILED;
        $this->file->embedding_status = FileStatus::FAILED;
        $this->file->save();

        event(new FileProgressUpdated($this->file));

        $this->release(10);
    }
}
