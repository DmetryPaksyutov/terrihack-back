<?php

namespace App\Services\Resume\DTO\Enums;

enum Sort: string
{
    case RELEVANCE = 'relevance';
    case BY_UPDATED_AT = 'byUpdatedAt';
    case BY_SALARY_DESC = 'bySalaryDesc';
    case BY_SALARY_ASC = 'bySalaryAsc';
}
