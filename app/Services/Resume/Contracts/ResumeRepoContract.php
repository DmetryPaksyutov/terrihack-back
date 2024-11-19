<?php

namespace App\Services\Resume\Contracts;

use App\Services\Resume\DTO\EmployeeResumeDto;
use App\Services\Resume\DTO\SearchEmployeeResumeDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ResumeRepoContract
{
    public function createEmployeeResume(EmployeeResumeDto $dto): EmployeeResumeDto;

    public function searchByEmployeeResume(SearchEmployeeResumeDto $dto): LengthAwarePaginator;
}