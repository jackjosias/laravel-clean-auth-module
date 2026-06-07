<?php

declare(strict_types=1);

namespace App\Domain\Auth\ValueObjects;

final readonly class HashedPassword
{
    public function __construct(public string $hash) {}

    public function __toString(): string
    {
        return $this->hash;
    }
}
