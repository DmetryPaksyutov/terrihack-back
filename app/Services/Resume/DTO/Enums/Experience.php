<?php

namespace App\Services\Resume\DTO\Enums;

enum Experience: string
{
    case NO_EXPERIENCE = 'noExperience';
    case ONE_TO_THREE_YEARS = '1-3';
    case THREE_TO_SIX_YEARS = '3-6';
    case SIX_PLUS_YEARS = '6+';
}
