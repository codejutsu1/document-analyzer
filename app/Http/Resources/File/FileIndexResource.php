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
        return [  
            'id' => $this->id,
            'name' => $this->name ?? "Processing",
            'size' => ceil($this->size) . " MB",
            'pages' => $this->pages ?? "Processing",
            'status' => $this->status,
            'created_at' => $this->created_at->format('d M, Y'),
        ];
    }
}
