<?php

namespace App\Services\Resume;

use App\Services\Resume\Contracts\ResumeRepoContract;
use App\Services\Resume\DTO\EmployeeResumeDto;
use App\Services\Resume\DTO\SearchEmployeeResumeDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ResumeService
{
    public function __construct(protected ResumeRepoContract $repo)
    {

    }

    public function createEmployeeResume(EmployeeResumeDto $dto): EmployeeResumeDto
    {
        return $this->repo->createEmployeeResume($dto);
    }

    public function searchByEmployeeResume(SearchEmployeeResumeDto $dto): LengthAwarePaginator
    {
        return $this->repo->searchByEmployeeResume($dto);
    }
}