<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserWithTokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->emailVerifiedAt ? $this->emailVerifiedAt->toDateTimeString() : null,
            'password' => $this->password,
            'created_at' => $this->createdAt ? $this->createdAt->toDateTimeString() : null,
            'updated_at' => $this->updatedAt ? $this->updatedAt->toDateTimeString() : null,
            'token' => $this->token,
        ];
    }
}
