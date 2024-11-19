<?php

declare(strict_types=1);

namespace App\Services\UserAccountsService\DTO;

use App\Services\UserService\DTO\UserDTO;
use Illuminate\Support\Carbon;

readonly class UserAccountsDTO
{
    public function __construct(
        public ?string $id  = null,
        public int $userId,
        public string $type,
        public string $accountId,
        public ?string $profileImage = null,
        public ?string $data = null,
        public ?Carbon $createdAt = null,
        public ?Carbon $updatedAt = null,
        public ?UserDTO $user = null,
    ) {
    }
}
