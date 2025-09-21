<?php

namespace App\Services\Llm\Driver;

use App\Models\User;
use App\InteractWithLlm;

class OpenAIDriver implements InteractWithLlm
{
    public function __construct(){}

    public function prompt(array $messages, User $user): string
    {
        return '';
    }

    public function embeddings(?string $path = null, ?array $texts = null): array
    {
        return [];
    }
}
