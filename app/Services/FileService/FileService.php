<?php

namespace App\Services\FileService;

use App\Repositories\FileRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

readonly class FileService
{
    public function __construct(
        private FileRepository $fileRepository
    ) {
    }

    public function create(string $path)
    {
        return $this->fileRepository->create($path);
    }

    public function insert(Collection $uploadedFilesInfo): ?Collection
    {
        if ($uploadedFilesInfo->isEmpty()) {
            return null;
        }

        return $this->fileRepository->insert($uploadedFilesInfo);
    }

    public function getList(int $perPage, string $sortOrder): LengthAwarePaginator
    {
        return $this->fileRepository->getList($perPage, $sortOrder);
    }

    public function getById(string $id)
    {
        return $this->fileRepository->getById($id);
    }
}
