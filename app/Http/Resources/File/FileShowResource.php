<?php

namespace App\Http\Resources\File;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'size' => $this->size,
            'author' => $this->author,
            'pages' => $this->pages,
            'status' => $this->status,
            'chunking_status' => $this->chunking_status,
            'embedding_status' => $this->embedding_status,
            'storage_status' => $this->storage_status,
            'created_at' => $this->created_at->format('d M, Y'),
            'type' => strtoUpper($this->type->value),
        ];
    }
}
