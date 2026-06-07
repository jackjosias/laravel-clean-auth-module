<?php

declare(strict_types=1);

namespace App\Domain\Auth\Exceptions;

use DomainException;

final class WeakPasswordException extends DomainException
{
    public static function withScore(int $score): self
    {
        return new self(
            "Password is too weak ({$score}/4). Use at least 10 characters with uppercase, numbers, and symbols."
        );
    }
}
