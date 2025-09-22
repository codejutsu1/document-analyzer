<?php

namespace App\Services\VectorDatabase\Data;

use Spatie\LaravelData\Data;

class QdrantSearchPayload extends Data
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly array $vector,
        public readonly int $limit,
    ) {}
}
