<?php

namespace App\Services\UserService;

use App\Repositories\UserRepository;
use App\Services\UserService\DTO\UserDTO;

readonly class UserService
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function getById(int $id)
    {
       return $this->userRepository->getById($id);
    }

    public function create(UserDTO $DTO): UserDTO
    {
       return $this->userRepository->create($DTO);
    }
}
