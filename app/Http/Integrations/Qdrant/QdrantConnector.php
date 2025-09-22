<?php

namespace App\Http\Integrations\Qdrant;

use Saloon\Http\Auth\HeaderAuthenticator;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class QdrantConnector extends Connector
{
    use AcceptsJson;

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {
        return config('services.qdrant.url').'/collections';
    }

    /**
     * Default headers for every request
     */
    protected function defaultHeaders(): array
    {
        return [];
    }

    /**
     * Default HTTP client options
     */
    protected function defaultConfig(): array
    {
        return [];
    }

    protected function defaultAuth(): HeaderAuthenticator
    {
        return new HeaderAuthenticator(
            'Bearer '.config('services.qdrant.api_key')
        );
    }
}
