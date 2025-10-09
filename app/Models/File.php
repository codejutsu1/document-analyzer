<?php

namespace App\Models;

use App\Enums\FileStatus;
use App\Enums\FileType;
use App\Traits\HasUuidColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'total_chunks',
        'processed_chunks',
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
            'total_chunks' => 'integer',
            'processed_chunks' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }
}
