<?php

declare(strict_types=1);

namespace App\Application\Auth\UseCases;

use App\Application\Auth\Commands\LoginUserCommand;
use App\Application\Auth\Responses\LoginUserResponse;
use App\Domain\Auth\Contracts\PasswordHasherInterface;
use App\Domain\Auth\Contracts\UserRepositoryInterface;
use App\Domain\Auth\Entities\User;
use App\Domain\Auth\Exceptions\InvalidCredentialsException;
use App\Domain\Auth\ValueObjects\Email;
use App\Domain\Auth\ValueObjects\HashedPassword;

final class LoginUserUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly PasswordHasherInterface $passwordHasher,
    ) {}

    public function execute(LoginUserCommand $command): LoginUserResponse
    {
        $user = $this->userRepository->findByEmail(new Email($command->email));
        $password = $this->passwordForVerification($user, $command->plainPassword);
        $passwordMatches = $this->passwordHasher->verify($command->plainPassword, $password);

        if (! $user || ! $passwordMatches) {
            throw InvalidCredentialsException::create();
        }

        return new LoginUserResponse($user, bin2hex(random_bytes(32)));
    }

    private function passwordForVerification(?User $user, string $plaintext): HashedPassword
    {
        return $user?->getPassword() ?? $this->passwordHasher->hash($plaintext);
    }
}
