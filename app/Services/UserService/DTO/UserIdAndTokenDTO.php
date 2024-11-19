<?php

declare(strict_types=1);

namespace App\Services\UserService\DTO;

readonly class UserIdAndTokenDTO
{
    public function __construct(
        public int $id,
        public string $token,
    ) {
    }
}
