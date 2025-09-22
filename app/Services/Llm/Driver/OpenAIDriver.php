<?php

namespace App\Services\Llm\Driver;

use App\Contracts\InteractWithLlm;
use App\Models\User;

class OpenAIDriver implements InteractWithLlm
{
    public function __construct() {}

    public function prompt(array $messages, User $user): string
    {
        return '';
    }

    public function embed(?string $texts = null, ?string $path = null): array
    {
        return [];
    }
}
