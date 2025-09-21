<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessChunkJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected array $chunk, 
        protected string $filename
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // $embedding = app(GeminiService::class)->embed($this->chunk['text']);
    
            // Store in vector DB
            // app(VectorDbService::class)->upsert([
            //     'id' => $this->filename . '_chunk_' . $this->chunk['chunk_index'],
            //     'vector' => $embedding,
            //     'metadata' => [
            //         'doc_id' => $this->filename,
            //         'page' => $this->chunk['page'] ?? null,
            //         'chunk_index' => $this->chunk['chunk_index'],
            //     ],
            //     'text' => $this->chunk['text'],
            // ]);
        } catch (\Throwable $e) {
            Log::error("Chunk failed: " . $e->getMessage());
            $this->release(10); // retry in 10s
        }
    }
}
