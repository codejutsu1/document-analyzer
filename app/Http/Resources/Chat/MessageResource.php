<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $message = $this->resource;

        return [
            'id' => $message->id,
            'uuid' => $message->uuid,
            'participant' => $message->participant,
            'message' => $message->message,
            'created_at' => $message->created_at->format('d M, Y'),
        ];
    }
}
