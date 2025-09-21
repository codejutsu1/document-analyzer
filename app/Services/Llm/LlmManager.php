<?php

namespace App\Services\Llm;

use Prism\Prism\Providers\OpenAI\OpenAI;
use App\Services\Llm\Driver\GeminiDriver;
use App\Services\Llm\Driver\OpenAIDriver;
use Illuminate\Support\Manager;

class LlmManager extends Manager
{
    public function getDefaultDriver()
    {
        return config('app.services.llm.driver', 'gemini');
    }

    public function createChatgptDriver()
    {
        return new OpenAIDriver;
    }

    public function createGeminiDriver()
    {
        return new GeminiDriver;
    }
}
