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
        $must = [];

        if (! empty($this->qdrantPayload->doc_id)) {
            $must[] = [
                'key' => 'doc_id',
                'match' => ['value' => $this->qdrantPayload->doc_id],
            ];
        }

        $body = [
            'vector' => $this->qdrantPayload->vector,
            'limit' => $this->qdrantPayload->limit,
            'with_payload' => true,
        ];

        if (! empty($must)) {
            $body['filter'] = [
                'must' => $must,
            ];
        }

        return $body;
    }

    protected function random(): void
    {
        // $response = Http::withToken(
        //     ""
        //   )->put(
        //     "",
        //     [
        //       "vectors" => [
        //         "size" => 384,
        //         "distance" => "Cosine"
        //       ]
        //     ]
        //   );

        // $response = Http::withToken(
        //     ""
        //   )->put(
        //     "",
        //     [
        //       "field_name" => "doc_id",
        //       "field_schema" => "keyword"
        //     ]
        //   );

    }
}
