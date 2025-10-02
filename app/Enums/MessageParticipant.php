<?php

namespace App\Enums;

enum MessageParticipant: string
{
    case USER = 'user';

    case ASSISTANT = 'assistant';
}
