<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuidColumn
{
    protected bool $hasUuidColumn = true;

    protected string $uuidColumnName = 'uuid';

    public static function bootHasUuidColumn(): void
    {
        static::creating(function (self $model) {
            if ($model->hasUuidColumn) {
                $model->attributes[$model->uuidColumnName] = Str::uuid();
            }
        });
    }
}
