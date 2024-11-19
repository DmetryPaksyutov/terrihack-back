<?php

namespace App\Repositories;

use App\Models\UserAccount;
use App\Services\UserAccountsService\DTO\UserAccountsDTO;

class UserAccountsRepository
{
    public function getByAccountId(string $accountId)
    {
        return UserAccount::query()->where(['account_id' => $accountId])->first();
    }

    public function getByUserId(int $userId)
    {
        return UserAccount::query()->where(['user_id' => $userId])->first();
    }

    public function existByAccountId(string $accountId): bool
    {
        return UserAccount::query()->where(['account_id' => $accountId])->exists();
    }

    public function getByAccountIdWithUser(string $accountId): ?UserAccount
    {
        return UserAccount::query()
            ->with('user')
            ->where(['account_id' => $accountId])
            ->first();
    }

    public function create(UserAccountsDTO $DTO)
    {
        return UserAccount::query()->create([
            'user_id' => $DTO->userId,
            'type' => $DTO->type,
            'account_id' => $DTO->accountId,
            'profile_image' => $DTO->profileImage,
            'data' => $DTO->data,
        ]);
    }
}
