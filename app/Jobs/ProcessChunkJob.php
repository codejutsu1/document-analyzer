<?php

namespace App\Jobs;

use App\Events\FileProgressUpdated;
use App\Events\FilesStatusUpdated;
use App\Facades\Llm;
use App\Facades\VectorDatabase;
use App\Models\File;
use App\Services\VectorDatabase\Data\QdrantUpsertPayload;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class ProcessChunkJob implements ShouldQueue
{
    use Batchable, Queueable;

    public $tries = 6;

    public $backoff = 20;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public array $chunk,
        public int $fileId,
    ) {}

    public function middleware(): array
    {
        // return [];
        return [
            new RateLimited('pdf-processing'),
        ];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $file = File::findOrFail($this->fileId);

        $embedding = Llm::embed($this->chunk['text']);

        $id = Uuid::uuid5(
            Uuid::NAMESPACE_DNS,
            $this->fileId.'-'.$this->chunk['chunk_index']
        )->toString();

        $payload = QdrantUpsertPayload::from([
            // 'id' => $this->filename . '_chunk_' . $this->chunk['chunk_index'],
            'id' => $id,
            'vector' => $embedding,
            'payload' => [
                'doc_id' => $file->path,
                'page' => $this->chunk['page'] ?? null,
                'chunk_index' => $this->chunk['chunk_index'],
                'text' => $this->chunk['text'],
            ],
        ]);

        Log::info($id);

        Log::info('Sending chunk '.$this->chunk['chunk_index'].' to vector database');

        VectorDatabase::upsert($payload);

        Log::info('Chunk '.$this->chunk['chunk_index'].' stored in vector database');

        Log::info('Chunk '.$this->chunk['chunk_index'].' processed successfully');

        $file->increment('processed_chunks');

        event(new FilesStatusUpdated($file));

        // '595c678e-b6b3-4dac-8a51-b316cf03a50a';
    }

    public function failed(\Throwable $exception): void
    {
        $file = File::findOrFail($this->fileId);

        Log::error('Chunk failed: '.$exception->getMessage());

        // event(new FileProgressUpdated($file));
    }
}
