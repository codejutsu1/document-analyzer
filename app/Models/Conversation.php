<?php

namespace App\Models;

use App\Traits\HasUuidColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conversation extends Model
{
    use HasUuidColumn;
    
    protected $fillable = [
        'user_id',
        'file_id',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
