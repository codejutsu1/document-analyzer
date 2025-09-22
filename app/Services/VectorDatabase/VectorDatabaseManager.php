<?php

namespace App\Services\VectorDatabase;

use App\Contracts\InteractWithVectorDatabase;
use App\Services\VectorDatabase\Driver\QdrantDriver;
use Illuminate\Support\Manager;

class VectorDatabaseManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return config('app.services.vector_database.driver', 'qdrant');
    }

    public function createQdrantDriver(): InteractWithVectorDatabase
    {
        return new QdrantDriver;
    }
}
