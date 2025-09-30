<?php

namespace App\Models;

use App\Enums\FileType;
use App\Enums\FileStatus;
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
        'status',
        'chunking_status',
        'embedding_status',
        'storage_status',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected function casts(): array
    {
        return [
            'type' => FileType::class,
            'status' => FileStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
