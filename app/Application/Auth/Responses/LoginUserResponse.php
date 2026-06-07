<?php

declare(strict_types=1);

namespace App\Application\Auth\Responses;

use App\Domain\Auth\Entities\User;

final readonly class LoginUserResponse
{
    public function __construct(
        public User $user,
        public string $sessionToken,
    ) {}
}
