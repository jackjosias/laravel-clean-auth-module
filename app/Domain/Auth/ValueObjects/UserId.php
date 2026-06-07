<?php

declare(strict_types=1);

namespace App\Domain\Auth\ValueObjects;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

final readonly class UserId
{
    public function __construct(public string $value)
    {
        if (! preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $value)) {
            throw new InvalidArgumentException('Invalid UserId: UUID v4 is required.');
        }
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
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
