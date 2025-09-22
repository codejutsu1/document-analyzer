<?php

namespace App\Jobs;

use App\Facades\Llm;
use App\Facades\VectorDatabase;
use App\Services\VectorDatabase\Data\QdrantUpsertPayload;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessChunkJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected array $chunk,
        protected string $filename
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $embedding = Llm::embed($this->chunk['text']);

            $uuid = Str::uuid();

            $payload = QdrantUpsertPayload::from([
                // 'id' => $this->filename . '_chunk_' . $this->chunk['chunk_index'],
                'id' => $uuid,
                'vector' => $embedding,
                'payload' => [
                    'doc_id' => $this->filename,
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

            // '595c678e-b6b3-4dac-8a51-b316cf03a50a';
        } catch (\Throwable $e) {
            Log::error('Chunk failed: '.$e->getMessage());
            $this->release(10);
        }
    }
}
