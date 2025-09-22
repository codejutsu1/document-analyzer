<?php

namespace App\Http\Integrations\Qdrant\Requests;

use App\Services\VectorDatabase\Data\QdrantUpsertPayload;
use Illuminate\Support\Facades\Log;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Plugins\AcceptsJson;

class UpsertRequest extends Request implements HasBody
{
    use AcceptsJson;
    use HasJsonBody;

    public function __construct(
        protected QdrantUpsertPayload $qdrantPayload
    ) {}

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::PUT;

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        $collectionName = config('services.qdrant.collection_name');

        Log::info('Collection name: '.$collectionName);

        return "/$collectionName/points?wait=true";
    }

    public function defaultBody(): array
    {
        return [
            'points' => [
                [
                    'id' => $this->qdrantPayload->id,
                    'vector' => $this->qdrantPayload->vector,
                    'payload' => $this->qdrantPayload->payload,
                ],
            ],
        ];
    }
}
