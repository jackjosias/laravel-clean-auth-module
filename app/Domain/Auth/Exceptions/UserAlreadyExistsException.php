<?php

declare(strict_types=1);

namespace App\Domain\Auth\Exceptions;

use DomainException;

final class UserAlreadyExistsException extends DomainException
{
    public static function withEmail(string $email): self
    {
        return new self("An account already exists for: {$email}");
    }
}
