<?php

namespace App\Services\Resume\DTO\Enums;

enum EmploymentType: string
{
    case FULL_TIME = 'fullTime';
    case PART_TIME = 'partTime';
    case PROJECT_BASED = 'projectBased';
    case VOLUNTEERING = 'volunteering';
    case CIVIL_LAW_CONTRACT = 'civilLawContract';
    case INTERNSHIP = 'internship';
}
