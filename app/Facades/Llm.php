<?php

namespace App\Facades;  

use App\Services\Llm\LlmManager;
use Illuminate\Support\Facades\Facade;

class Llm extends Facade
{
    protected static function getFacadeAccessor()
    {
        return LlmManager::class;
    }
}
