<?php

namespace App\Services\Resume\DTO\Enums;

enum TimePeriod: string
{
    case ALL_TIME = 'allTime';
    case MONTH = 'month';
    case WEEK = 'week';
    case THREE_DAYS = '3days';
    case LAST_DAY = 'lastDay';
}
