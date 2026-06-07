<?php

declare(strict_types=1);

namespace App\Application\Auth\Commands;

final readonly class RegisterUserCommand
{
    public function __construct(
        public string $name,
        public string $email,
        public string $plainPassword,
    ) {}
}
