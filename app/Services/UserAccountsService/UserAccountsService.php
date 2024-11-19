<?php

namespace App\Services\UserAccountsService;

use App\Repositories\UserAccountsRepository;
use App\Services\UserAccountsService\DTO\UserAccountsDTO;
use App\Services\UserService\DTO\UserDTO;

readonly class UserAccountsService
{
    public function __construct(
        private UserAccountsRepository $userAccountsRepository
    ) {
    }

    public function getByAccountIdWithUser(string $accountId): ?UserAccountsDTO
    {
        $userAccount = $this->userAccountsRepository->getByAccountIdWithUser($accountId);

        if ($userAccount === null) {
            return null;
        }

       return new UserAccountsDTO(
           id: $userAccount->id,
           userId: $userAccount->user_id,
           type: $userAccount->type,
           accountId: $userAccount->account_id,
           profileImage: $userAccount->profile_image,
           data: $userAccount->data,
           createdAt: $userAccount->created_at,
           updatedAt: $userAccount->updated_at,
           user: new UserDTO(
               id: $userAccount->user->id,
               name: $userAccount->user->name,
           )
       );
    }

    public function create(UserAccountsDTO $DTO)
    {
        $this->userAccountsRepository->create($DTO);
    }
}
