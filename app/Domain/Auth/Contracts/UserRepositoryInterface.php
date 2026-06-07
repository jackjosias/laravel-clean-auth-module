<?php

declare(strict_types=1);

namespace App\Domain\Auth\Contracts;

use App\Domain\Auth\Entities\User;
use App\Domain\Auth\ValueObjects\Email;
use App\Domain\Auth\ValueObjects\UserId;

interface UserRepositoryInterface
{
    public function findById(UserId $id): ?User;

    public function findByEmail(Email $email): ?User;

    public function save(User $user): void;

    public function existsByEmail(Email $email): bool;
}
