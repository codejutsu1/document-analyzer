<?php

namespace App\Http\Integrations\Qdrant\Requests;

use App\Services\VectorDatabase\Data\QdrantSearchPayload;
use Illuminate\Support\Facades\Log;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Plugins\AcceptsJson;

class QueryRequest extends Request implements HasBody
{
    use AcceptsJson;
    use HasJsonBody;

    public function __construct(
        protected QdrantSearchPayload $qdrantPayload
    ) {}

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::POST;

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        $collectionName = config('services.qdrant.collection_name');

        Log::info('Collection name: '.$collectionName);

        return "/$collectionName/points/search";
    }

    public function defaultBody(): array
    {
        return [
            'vector' => $this->qdrantPayload->vector,
            'limit' => $this->qdrantPayload->limit,
            'with_payload' => true,
        ];
    }
}
