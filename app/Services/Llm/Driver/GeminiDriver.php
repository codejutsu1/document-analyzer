<?php

namespace App\Services\Llm\Driver;

use App\Models\User;
use Prism\Prism\Prism;
use App\InteractWithLlm;
use Prism\Prism\Enums\Provider;
use Illuminate\Support\Facades\Log;
use Prism\Prism\Exceptions\PrismException;

class GeminiDriver implements InteractWithLlm
{
    public function __construct() {}

    public function prompt(array $prismMessages, User $user): string
    {
        return Prism::text()
            ->using(Provider::Gemini, 'gemini-2.5-flash')
            ->withMessages($prismMessages)
            ->asText()->text;
    }

    public function embeddings(?string $path = null, ?array $texts = null): array
    {
        try {
            $response = Prism::embeddings()
                ->using(Provider::Gemini, 'gemini-embedding-001')
                ->fromArray($texts)
                // ->withClientOptions(['timeout' => 30]) 
                ->withClientRetry(3, 100) 
                ->withProviderOptions(['taskType' => 'RETRIEVAL_DOCUMENT'])
                ->asEmbeddings();

            return $response->embeddings;
        }catch (PrismException $e) {
            Log::error('Embeddings generation failed:', [
                'error' => $e->getMessage()
            ]);

            return [];
        }
    }
}
