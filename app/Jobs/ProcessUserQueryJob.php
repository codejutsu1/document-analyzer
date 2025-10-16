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
use Prism\Prism\ValueObjects\Messages\AssistantMessage;
use Prism\Prism\ValueObjects\Messages\UserMessage;

class ProcessUserQueryJob implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public $backoff = 10;

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

        Log::info(['results count' => $results]);

        Log::info('Results', ['results' => $results]);

        $context = '';

        foreach ($results as $i => $h) {
            $p = $h['payload'];

            $pageLabel = '';

            $pages = $p['pages_spanned'] ?? [];

            if (is_array($pages)) {
                if (count($pages) > 1) {
                    $pageLabel = 'pages '.implode(', ', $pages);
                } elseif (count($pages) === 1) {
                    $pageLabel = 'page '.$pages[0];
                } else {
                    $pageLabel = 'page '.($p['page'] ?? 'N/A');
                }
            } else {
                $pageLabel = 'page '.$pages;
            }

            Log::info(['pageLabel' => $pageLabel]);

            $context .= "---CHUNK {$i}---\n[doc: {$p['doc_id']}, {$pageLabel}]\n".$p['text']."\n\n";
        }

        Log::info('Context', ['context' => $context]);

        /** @var Conversation $conversation */
        $conversation = $this->message->conversation;

        $prismMessages = $conversation->messages->map(function ($message) {
            /** @phpstan-ignore-next-line */
            return $message->participant == MessageParticipant::USER
                /** @phpstan-ignore-next-line */
                ? new UserMessage($message->message)
                /** @phpstan-ignore-next-line */
                : new AssistantMessage($message->message);
        })->all();

        $prompt = "\n\nContext:\n{$context}\nUser: {$this->message->message}\nAnswer:";

        $prismMessages[] = new UserMessage($prompt);

        $llmResponse = Llm::prompt(prismMessages: $prismMessages);

        Log::info('LLM Response', ['llmResponse' => $llmResponse]);

        $conversation->messages()->create([
            'user_id' => $this->message->user_id,
            'message' => $llmResponse,
            'participant' => MessageParticipant::ASSISTANT,
        ]);

        event(new MessageCreated($conversation));
    }
}
