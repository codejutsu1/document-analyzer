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
        $file = $this->resource;

        return [
            'id' => $file->id,
            'uuid' => $file->uuid,
            'name' => $file->name,
            'size' => $file->size,
            'author' => $file->author,
            'pages' => $file->pages,
            'status' => $file->status,
            'progress' => $file->total_chunks > 0
                                ? round(($file->processed_chunks / $file->total_chunks) * 100)
                                : 0,
            'created_at' => $file->created_at->format('d M, Y'),
            'type' => strtoupper($file->type->value),
        ];
    }
}
