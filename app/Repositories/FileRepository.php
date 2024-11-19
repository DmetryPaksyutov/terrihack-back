<?php

namespace App\Repositories;

use App\Models\Resume;
use App\Services\FileService\DTO\UpdateFileDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class FileRepository
{
    public function create(string $pdfPath)
    {
        return Resume::query()->create([
            'pdf_path' => $pdfPath,
            'hash' => hash_file('sha256', $pdfPath)
        ]);
    }

    public function getById(string $id)
    {
        return Resume::query()->where(['id' => $id])->first();
    }

    public function insert(Collection $filesInfo): ?Collection
    {
        $data = $filesInfo
            ->map(fn($fileInfo) => [
                'id' => Str::uuid()->toString(),
                'name' => $fileInfo['fileName'],
                'pdf_path' => $fileInfo['path'],
                'txt_path' => null,
                'status' => Resume::STATUS_LOADED,
                'status_text' => null,
                'hash' => hash_file('sha256', storage_path($fileInfo['path'])),
                'created_at' => now(),
                'updated_at' => now()
            ]);

        $hashes = $data->pluck('hash');

        $includeFiles = Resume::query()->whereIn('hash', $hashes)->pluck('hash')->toArray();

        $insertData = $data->filter(fn($item) => !in_array($item['hash'], $includeFiles)
        )->map(fn($item) => [
            'id' => Str::uuid()->toString(),
            'name' => $item['name'],
            'pdf_path' => $item['pdf_path'],
            'txt_path' => null,
            'status' => Resume::STATUS_LOADED,
            'status_text' => null,
            'hash' => $item['hash'],
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();

        if (empty($insertData)) {
            return null;
        }

        Resume::query()->insert($insertData);

        return collect($insertData)->pluck('id');
    }

    public function updateById(string $id, UpdateFileDTO $DTO): int
    {
        return Resume::query()->where(['id' => $id])->update($DTO->getArray());
    }

    public function getList(int $perPage, string $sortOrder): LengthAwarePaginator
    {
        return Resume::query()->orderBy('created_at', $sortOrder)->paginate($perPage);
    }
}
