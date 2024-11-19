<?php

namespace App\Services\Resume\DTO\Enums;

enum WorkSchedule: string
{
    case FULL_DAY = 'fullDay';
    case SHIFT = 'shift';
    case FLEXIBLE = 'flexible';
    case REMOTE = 'remote';
    case ROTATIONAL = 'rotational';
}
