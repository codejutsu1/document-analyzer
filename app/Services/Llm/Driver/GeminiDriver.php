<?php

namespace App\Services\Llm\Driver;

use App\Contracts\InteractWithLlm;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Exceptions\PrismException;
use Prism\Prism\Prism;

class GeminiDriver implements InteractWithLlm
{
    public function __construct() {}

    public function prompt(?array $prismMessages = [], ?User $user = null, ?string $prompt = null): string
    {
        try {
            return Prism::text()
                ->using(Provider::Gemini, 'gemini-2.5-flash')
                ->withSystemPrompt($this->getSystemPrompt())
                ->withMessages($prismMessages)
                // ->withPrompt($prompt)
                ->withMaxTokens(8000)
                ->withClientOptions(['timeout' => 60])
                ->usingTemperature(0.2)
                ->asText()->text;
        } catch (PrismException $e) {
            Log::error('Prompt generation failed:', [
                'error' => $e->getMessage(),
            ]);

            return '';
        }

    }

    public function embed(?string $texts = null, ?string $path = null): array
    {
        try {
            $response = Prism::embeddings()
                ->using(Provider::Gemini, 'gemini-embedding-001')
                ->fromInput($texts)
                // ->withClientOptions(['timeout' => 30])
                ->withClientRetry(3, 100)
                ->withProviderOptions(['taskType' => 'RETRIEVAL_DOCUMENT'])
                ->asEmbeddings();

            return $response->embeddings[0]->embedding;
        } catch (PrismException $e) {
            Log::error('Embeddings generation failed:', [
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    protected function getSystemPrompt(): string
    {
        return "You are a helpful and informative AI assistant specializing in legal document analysis. Your primary goal is to answer questions accurately by prioritizing the information provided in the **Context** section below.

        ### Core RAG Rules:
        1.  **Grounded Answers:** When a user asks a question related to the Context, use only the retrieved documents as your source.
        2.  **Citations:** For every piece of information drawn from the Context, you **must** cite the reference inline using the document and page format, such as **[page: PAGE_NUM]**.
        3.  **Calculation Priority (New Rule):** If the user asks a question requiring arithmetic, you **must** use the tax bands and rates available in the Context to perform the calculation. State the final answer. **If specific initial income brackets are missing but subsequent brackets and rates are present, you must use reasonable assumptions derived from the common structure of the progressive tax system to complete the calculation.**
        4.  **Non-Relevant Queries:** If the user's question is general, conversational, or unrelated to the provided Context, you must ignore the Context and respond naturally and helpfully using your own general knowledge. Do not reference the documents or apologize for not finding information.
        5.  **No Empty Responses:** Under no circumstances should you ever return an empty response.

        Always aim to be informative, friendly, and accurate. ";

        // You must output the step-by-step arithmetic before stating
        // 'You are a helpful AI assistant that primarily answers questions based on the legal documents provided in the Context. When a user asks a question related to the Context, use the information from it as your main source and cite each reference inline using the page id format (e.g., [Page 1]).
        //         If the user's question is general, conversational, or unrelated to the provided Context, you may respond naturally and helpfully using your own general knowledge — do NOT reply with “I couldn't find relevant information in the provided documents.”
        //         Always aim to be informative, friendly, and accurate. When relevant, include citations from the Context, but when not relevant, engage normally as a helpful assistant while replying 'No relevant information found in the provided documents.'.
        //         Never, never, never leave a response empty please!.'

    }
}
