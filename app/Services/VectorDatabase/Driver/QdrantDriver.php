<?php

namespace App\Services\VectorDatabase\Driver;

use App\Contracts\InteractWithVectorDatabase;
use App\Http\Integrations\Qdrant\QdrantConnector;
use App\Http\Integrations\Qdrant\Requests\QueryRequest;
use App\Http\Integrations\Qdrant\Requests\UpsertRequest;
use App\Services\VectorDatabase\Data\QdrantSearchPayload;
use App\Services\VectorDatabase\Data\QdrantUpsertPayload;
use Illuminate\Support\Facades\Log;

class QdrantDriver implements InteractWithVectorDatabase
{
    protected QdrantConnector $connector;

    public function __construct()
    {
        $this->connector = new QdrantConnector;
    }

    public function upsert(QdrantUpsertPayload $data): void
    {
        $response = $this->connector->send(new UpsertRequest($data));

        if ($response->failed()) {
            Log::error('Vector database upsert failed', [
                'status' => $response->status(),
            ]);
            // throw new PaymentException(
            //     message: 'Failed to initiate transaction.',
            //     provider: TransactionPaymentProvider::Paystack->value,
            //     response_data: $response->json()
            // );
        }

        Log::info('Vector database upsert response', [
            'status' => $response->status(),
        ]);
    }

    public function search(QdrantSearchPayload $data): array
    {
        $response = $this->connector->send(new QueryRequest($data));

        if ($response->failed()) {
            Log::error('Vector database search failed', [
                'status' => $response->status(),
                // 'body' => $response->body(),
                // 'json' => $response->json(),
            ]);
        }

        Log::info('Vector database search response', [
            'status' => $response->status(),
        ]);

        return $response->json()['result'];
    }
}
