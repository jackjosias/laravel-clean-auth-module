<?php

declare(strict_types=1);

namespace App\Domain\Auth\ValueObjects;

use App\Domain\Auth\Exceptions\InvalidEmailException;

final readonly class Email
{
    public string $value;

    public function __construct(string $email)
    {
        $normalized = mb_strtolower(trim($email));

        if (! filter_var($normalized, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException("Invalid email address: {$email}");
        }

        $this->value = $normalized;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
