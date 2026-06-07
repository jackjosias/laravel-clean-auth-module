<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Security;

use App\Domain\Auth\Contracts\PasswordHasherInterface;
use App\Domain\Auth\ValueObjects\HashedPassword;

final class ArgonPasswordHasher implements PasswordHasherInterface
{
    public function hash(string $plaintext): HashedPassword
    {
        $hash = password_hash($plaintext, PASSWORD_ARGON2ID, $this->options());

        return new HashedPassword($hash);
    }

    public function verify(string $plaintext, HashedPassword $hashed): bool
    {
        return password_verify($plaintext, $hashed->hash);
    }

    /**
     * @return array{memory_cost: int, time_cost: int, threads: int}
     */
    private function options(): array
    {
        return [
            'memory_cost' => (int) config('auth-module.argon2id.memory_cost', 65536),
            'time_cost' => (int) config('auth-module.argon2id.time_cost', 4),
            'threads' => (int) config('auth-module.argon2id.threads', 1),
        ];
    }
}
