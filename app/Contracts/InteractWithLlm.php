<?php

namespace App\Contracts;

use App\Models\User;

interface InteractWithLlm
{
    public function prompt(array $messages, User $user): string;

    public function embed(?string $texts = null, ?string $path = null): array;
}
