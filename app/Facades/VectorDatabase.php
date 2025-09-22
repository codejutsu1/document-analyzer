<?php

namespace App\Facades;

use App\Services\VectorDatabase\VectorDatabaseManager;
use Illuminate\Support\Facades\Facade;

class VectorDatabase extends Facade
{
    protected static function getFacadeAccessor()
    {
        return VectorDatabaseManager::class;
    }
}
