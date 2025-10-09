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
                ->withSystemPrompt("You are a helpful AI assistant that primarily answers questions based on the legal documents provided in the Context. When a user asks a question related to the Context, use the information from it as your main source and cite each reference inline using the page id format (e.g., [Page 1]). 
                If the user's question is general, conversational, or unrelated to the provided Context, you may respond naturally and helpfully using your own general knowledge — do NOT reply with “I couldn't find relevant information in the provided documents.”
                Always aim to be informative, friendly, and accurate. When relevant, include citations from the Context, but when not relevant, engage normally as a helpful assistant while replying 'No relevant information found in the provided documents.'.
                Never, never, never leave a response empty please!.")
                // ->withMessages($prismMessages)
                ->withPrompt($prompt)
                ->withMaxTokens(512)
                ->usingTemperature(0.0)
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
}
