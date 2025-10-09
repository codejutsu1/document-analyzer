<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $conversation = $this->resource;

        return [
            'id' => $conversation->id,
            'uuid' => $conversation->uuid,
            'message' => $conversation->getMessages($conversation->messages->first()->message),
            'created_at' => $conversation->created_at->format('d M, Y'),
        ];
    }

    protected function getMessages($text, $limit = 15): string
    {
        $words = explode(' ', $text);
        $first = array_slice($words, 0, $limit);

        return implode(' ', $first).'...';
    }
}
