<?php

declare(strict_types=1);

namespace App\Application\Auth\UseCases;

use App\Application\Auth\Commands\RegisterUserCommand;
use App\Application\Auth\Responses\RegisterUserResponse;
use App\Domain\Auth\Contracts\PasswordHasherInterface;
use App\Domain\Auth\Contracts\UserRepositoryInterface;
use App\Domain\Auth\Entities\User;
use App\Domain\Auth\Exceptions\UserAlreadyExistsException;
use App\Domain\Auth\Services\PasswordStrengthService;
use App\Domain\Auth\ValueObjects\Email;
use App\Domain\Auth\ValueObjects\UserId;
use DateTimeImmutable;

final class RegisterUserUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly PasswordHasherInterface $passwordHasher,
        private readonly PasswordStrengthService $strengthService,
    ) {}

    public function execute(RegisterUserCommand $command): RegisterUserResponse
    {
        $email = new Email($command->email);

        if ($this->userRepository->existsByEmail($email)) {
            throw UserAlreadyExistsException::withEmail($command->email);
        }

        $this->strengthService->ensureStrong($command->plainPassword);
        $user = $this->buildUser($command, $email);
        $this->userRepository->save($user);

        return new RegisterUserResponse($user);
    }

    private function buildUser(RegisterUserCommand $command, Email $email): User
    {
        return new User(
            id: UserId::generate(),
            email: $email,
            name: trim($command->name),
            password: $this->passwordHasher->hash($command->plainPassword),
            createdAt: new DateTimeImmutable,
        );
    }
}
