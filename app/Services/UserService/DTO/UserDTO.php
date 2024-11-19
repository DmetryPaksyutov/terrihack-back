<?php

declare(strict_types=1);

namespace App\Services\UserService\DTO;

use Illuminate\Support\Carbon;

readonly class UserDTO
{
    public function __construct(
        public ?int $id = null,
        public string $name,
        public ?string $email = null,
        public ?Carbon $emailVerifiedAt = null,
        public ?string $password = null,
        public ?string $rememberToken = null,
        public ?Carbon $createdAt = null,
        public ?Carbon $updatedAt = null,
    ) {
    }
}
