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
                ->withSystemPrompt("You are a helpful assistant that answers questions based only on the legal documents provided in the Context. 
             Use the Context strictly as your sole source of information, and cite each reference inline using the document ID format (e.g., [Doc1]). 
            If the Context does not contain information relevant to the user's question, reply exactly: \"I couldn't find relevant information in the provided documents.\" 
            Always provide a complete, helpful sentence and include citations whenever applicable. Never return an empty response")
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
