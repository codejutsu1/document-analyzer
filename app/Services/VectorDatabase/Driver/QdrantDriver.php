<?php

namespace App\Services\VectorDatabase\Driver;

use Illuminate\Support\Facades\Log;
use App\Contracts\InteractWithVectorDatabase;
use App\Http\Integrations\Qdrant\QdrantConnector;
use App\Http\Integrations\Qdrant\Requests\QueryRequest;
use App\Http\Integrations\Qdrant\Requests\UpsertRequest;
use App\Services\VectorDatabase\Data\QdrantSearchPayload;
use App\Services\VectorDatabase\Data\QdrantUpsertPayload;

class QdrantDriver implements InteractWithVectorDatabase
{
    protected QdrantConnector $connector;

    public function __construct()
    {
        $this->connector = new QdrantConnector;
    }

    public function upsert(QdrantUpsertPayload $data): void
    {
        Log::info('Reached here driver');

        $response = $this->connector->send(new UpsertRequest($data));

        if ($response->failed()) {
            Log::error('Vector database upsert failed', [
                'status' => $response->status(),
                'body' => $response->body(),   // raw body (could be text, empty, or JSON)
                'json' => $response->json(),   // attempt JSON decode (null if invalid)
                'headers' => $response->headers(),
            ]);
            // throw new PaymentException(
            //     message: 'Failed to initiate transaction.',
            //     provider: TransactionPaymentProvider::Paystack->value,
            //     response_data: $response->json()
            // );
        }

        Log::info('Vector database upsert response', [
            'status' => $response->status(),
            'json' => $response->json(),
        ]);
    }

    public function search(QdrantSearchPayload $data): array
    {
        Log::info('Reached here driver');

        $response = $this->connector->send(new QueryRequest($data));

        if($response->failed()) {
            Log::error('Vector database search failed', [
                'status' => $response->status(),
                // 'body' => $response->body(),
                // 'json' => $response->json(),
            ]);
        }

        Log::info('Vector database search response', [
            'status' => $response->status(),
            'json' => $response->json(),
        ]);

        return $response->json()['result'];
    }
}
