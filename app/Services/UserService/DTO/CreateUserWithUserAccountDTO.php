<?php

declare(strict_types=1);

namespace App\Services\UserService\DTO;

readonly class CreateUserWithUserAccountDTO
{
    public function __construct(
        public string $type,
        public string $accountId,
        public ?string $name = null,
        public ?string $email = null,
        public ?string $profileImage = null,
        public ?string $data = null,
    ) {
    }
}
