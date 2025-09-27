<?php

namespace App\Models;

use App\Enums\FileType;
use App\Traits\HasUuidColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    use HasUuidColumn;

    protected $fillable = [
        'user_id',
        'path',
        'name',
        'size',
        'author',
        'pages',
        'type',
    ];

    protected function casts(): array
    {
        return [
            'type' => FileType::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
