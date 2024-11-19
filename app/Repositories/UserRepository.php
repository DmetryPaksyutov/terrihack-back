<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserAccount;
use App\Services\UserService\DTO\CreateUserWithUserAccountDTO;
use App\Services\UserService\DTO\UserDTO;
use App\Services\UserService\DTO\UserIdAndTokenDTO;
use App\Services\UserService\DTO\UserWithTokenDTO;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    public function getById(int $id)
    {
        return User::query()->where(['id' => $id])->first();
    }

    public function create(UserDTO $DTO): UserDTO
    {
        $user = User::query()->create([
            'name' => $DTO->name,
            'email' => $DTO->email,
            'password' => $DTO->password,
        ]);

        return new UserDTO(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            emailVerifiedAt: $user->email_verified_at,
            createdAt: $user->created_at,
            updatedAt: $user->updated_at,
        );
    }

    public function createWithAccountAndTemporaryToken(CreateUserWithUserAccountDTO $DTO): UserIdAndTokenDTO
    {
        DB::beginTransaction();
        try {
            $user = User::query()->create([
                'name' => $DTO->name,
                'email' => $DTO->email,
            ]);

            UserAccount::query()->create([
                'user_id' => $user->id,
                'type' => $DTO->type,
                'account_id' => $DTO->accountId,
                'profile_image' => $DTO->profileImage,
                'data' => $DTO->data,
            ]);

            $token = $user->createToken('once', ['once'])->plainTextToken;

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return new UserIdAndTokenDTO(id: $user->id, token: $token);
    }


    public function getUserIdAndTokenByAccountId(string $accountId): ?UserIdAndTokenDTO
    {
        $userAccount = UserAccount::query()
            ->where(['account_id' => $accountId])
            ->first();

        if (!$userAccount) {
            return null;
        }

        $user = User::query()
            ->where(['id' => $userAccount->user_id])
            ->first();

        $token = $user->createToken('once', ['once'])->plainTextToken;

        return new UserIdAndTokenDTO(id: $user->id, token: $token);
    }

    public function getCreatePersistentTokenByUserId(int $id): UserWithTokenDTO
    {
        $user = User::query()
            ->where(['id' => $id])
            ->first();

        $user
            ->tokens()
            ->whereJsonContains('abilities', 'once')
            ->delete();

        $token = $user->createToken('user', ['user'])->plainTextToken;

        return new UserWithTokenDTO(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            emailVerifiedAt: $user->email_verified_at,
            createdAt: $user->created_at,
            updatedAt: $user->updated_at,
            token: $token,
        );
    }
}
