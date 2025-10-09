<?php

namespace App\Http\Resources\File;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileIndexResource extends JsonResource
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
            'name' => $file->name ?? 'Processing',
            'size' => ceil($file->size).' MB',
            'pages' => $file->pages ?? 'Processing',
            'status' => $file->status,
            'created_at' => $file->created_at->format('d M, Y'),
        ];
    }
}
