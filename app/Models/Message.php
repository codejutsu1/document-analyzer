<?php

namespace App\Models;

use App\Traits\HasUuidColumn;
use App\Enums\MessageParticipant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasUuidColumn;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'message',
        'participant',
    ];

    protected function casts(): array
    {
        return [
            'participant' => MessageParticipant::class,
        ];
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
