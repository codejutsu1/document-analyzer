<?php

namespace App;

use App\Models\User;

interface InteractWithLlm
{
    public function prompt(array $messages, User $user): string;

    public function embeddings(?string $path = null, ?array $texts = null): array;
}
