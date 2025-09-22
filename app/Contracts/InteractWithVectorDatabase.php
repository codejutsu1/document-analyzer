<?php

namespace App\Contracts;

interface InteractWithVectorDatabase
{
    // public function upsert(): void;
    public function search(array $data): array;
}
