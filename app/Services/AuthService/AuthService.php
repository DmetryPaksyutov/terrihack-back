<?php

namespace App\Services\AuthService;

use App\Repositories\UserRepository;
use App\Services\UserService\DTO\CreateUserWithUserAccountDTO;
use App\Services\UserService\DTO\UserIdAndTokenDTO;
use App\Services\UserService\DTO\UserWithTokenDTO;
use Exception;
use Laravel\Socialite\Contracts\User;

readonly class AuthService
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    /**
     * @throws Exception
     */
    public function authorizeOrRegister(User $socialiteUser, string $type): UserIdAndTokenDTO
    {
        $userIdAndTokenDTO = $this->userRepository->getUserIdAndTokenByAccountId($socialiteUser->getId());

        if ($userIdAndTokenDTO === null) {
            $userIdAndTokenDTO = $this->userRepository->createWithAccountAndTemporaryToken(
                new CreateUserWithUserAccountDTO(
                    type: $type,
                    accountId: $socialiteUser->getId(),
                    name: $socialiteUser->getName() ?? $socialiteUser->getNickname(),
                    email: $socialiteUser->getEmail(),
                    profileImage: $socialiteUser->getAvatar(),
                )
            );
        }

        return $userIdAndTokenDTO;
    }

    public function auth(int $userId): UserWithTokenDTO
    {
      return  $this->userRepository->getCreatePersistentTokenByUserId($userId);
    }
}
