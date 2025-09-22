<?php

namespace App\Services\VectorDatabase\Data;

use Spatie\LaravelData\Data;

class QdrantUpsertPayload extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly array $vector,
        public readonly array $payload,
    ) {}
}
