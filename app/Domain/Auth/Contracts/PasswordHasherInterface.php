<?php

declare(strict_types=1);

namespace App\Domain\Auth\Contracts;

use App\Domain\Auth\ValueObjects\HashedPassword;

interface PasswordHasherInterface
{
    public function hash(string $plaintext): HashedPassword;

    public function verify(string $plaintext, HashedPassword $hashed): bool;
}
