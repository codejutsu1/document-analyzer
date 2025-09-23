<?php

namespace App\Jobs;

use App\Facades\Llm;
use App\Facades\VectorDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\VectorDatabase\Data\QdrantSearchPayload;

class ProcessUserQueryJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected string $query,
    )
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $embedResponse = Llm::embed(texts: $this->query);
        
        $payload = QdrantSearchPayload::from([
            'vector' => $embedResponse,
            'limit' => 10,
        ]);

        $results = VectorDatabase::search($payload);

        $context = "";

        foreach ($results as $i => $h) {
            $p = $h['payload'];
            $context .= "---CHUNK {$i}---\n[doc: {$p['doc_id']}, page: {$p['page']}] \n" . $p['text'] . "\n\n";
        }


        $prompt = "You are an assistant. Use ONLY the documents below and cite sources.\n\nContext:\n{$context}\nUser: {$this->query}\nAnswer:";

        $llmResponse = Llm::prompt(prompt: $prompt);

        Log::info("LLM response: " . $llmResponse);
    }   
}
