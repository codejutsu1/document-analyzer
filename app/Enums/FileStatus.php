<?php

namespace App\Enums;

enum FileStatus: string
{
    case PROCESSING = 'processing';
    case ACTIVE = 'active';
    case FAILED = 'failed';
    case PENDING = 'pending';
    case COMPLETED = 'completed';
}
