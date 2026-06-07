<?php

declare(strict_types=1);

namespace App\Application\Auth\Commands;

final readonly class LoginUserCommand
{
    public function __construct(
        public string $email,
        public string $plainPassword,
        public bool $remember = false,
    ) {}
}
