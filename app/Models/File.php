<?php

namespace App\Models;

use App\Traits\HasUuidColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    use HasUuidColumn;

    protected $fillable = [
        'user_id',
        'path',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
