<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Repositories;

use App\Domain\Auth\Contracts\UserRepositoryInterface;
use App\Domain\Auth\Entities\User as DomainUser;
use App\Domain\Auth\ValueObjects\Email;
use App\Domain\Auth\ValueObjects\HashedPassword;
use App\Domain\Auth\ValueObjects\UserId;
use App\Infrastructure\Auth\Models\UserEloquentModel;
use DateTimeImmutable;

final class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(UserId $id): ?DomainUser
    {
        $model = UserEloquentModel::query()->find($id->value);

        return $model instanceof UserEloquentModel ? $this->toDomain($model) : null;
    }

    public function findByEmail(Email $email): ?DomainUser
    {
        $model = UserEloquentModel::query()->where('email', $email->value)->first();

        return $model instanceof UserEloquentModel ? $this->toDomain($model) : null;
    }

    public function save(DomainUser $user): void
    {
        UserEloquentModel::query()->updateOrCreate(
            ['id' => $user->getId()->value],
            $this->payload($user),
        );
    }

    public function existsByEmail(Email $email): bool
    {
        return UserEloquentModel::query()->where('email', $email->value)->exists();
    }

    private function toDomain(UserEloquentModel $model): DomainUser
    {
        return new DomainUser(
            id: new UserId((string) $model->getAttribute('id')),
            email: new Email((string) $model->getAttribute('email')),
            name: (string) $model->getAttribute('name'),
            password: new HashedPassword((string) $model->getAttribute('password')),
            createdAt: new DateTimeImmutable((string) $model->getAttribute('created_at')),
        );
    }

    /**
     * @return array{name: string, email: string, password: string}
     */
    private function payload(DomainUser $user): array
    {
        return [
            'name' => $user->getName(),
            'email' => $user->getEmail()->value,
            'password' => $user->getPassword()->hash,
        ];
    }
}
