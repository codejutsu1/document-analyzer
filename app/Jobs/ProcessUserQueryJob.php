<?php

namespace App\Jobs;

use App\Enums\MessageParticipant;
use App\Events\MessageCreated;
use App\Facades\Llm;
use App\Facades\VectorDatabase;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\VectorDatabase\Data\QdrantSearchPayload;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessUserQueryJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Message $message,
        protected ?string $doc_id = null,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $embedResponse = Llm::embed(texts: $this->message->message);

        $payload = QdrantSearchPayload::from([
            'vector' => $embedResponse,
            'limit' => 10,
            'doc_id' => $this->doc_id,
        ]);

        $results = VectorDatabase::search($payload);

        Log::info('Results', ['results' => $results]);

        $context = '';

        foreach ($results as $i => $h) {
            $p = $h['payload'];
            $context .= "---CHUNK {$i}---\n[doc: {$p['doc_id']}, page: {$p['page']}] \n".$p['text']."\n\n";
        }

        $prompt = "\n\nContext:\n{$context}\nUser: {$this->message->message}\nAnswer:";

        $llmResponse = Llm::prompt(prompt: $prompt);

        Log::info('LLM Response', ['llmResponse' => $llmResponse]);

        /** @var Conversation $conversation */
        $conversation = $this->message->conversation;

        $conversation->messages()->create([
            'user_id' => $this->message->user_id,
            'message' => $llmResponse,
            'participant' => MessageParticipant::ASSISTANT,
        ]);

        event(new MessageCreated($conversation));
    }
}
