<?php

declare(strict_types=1);

namespace App\Domain\Auth\Entities;

use App\Domain\Auth\ValueObjects\Email;
use App\Domain\Auth\ValueObjects\HashedPassword;
use App\Domain\Auth\ValueObjects\UserId;
use DateTimeImmutable;

final class User
{
    public function __construct(
        private readonly UserId $id,
        private readonly Email $email,
        private readonly string $name,
        private HashedPassword $password,
        private readonly DateTimeImmutable $createdAt,
    ) {}

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPassword(): HashedPassword
    {
        return $this->password;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
