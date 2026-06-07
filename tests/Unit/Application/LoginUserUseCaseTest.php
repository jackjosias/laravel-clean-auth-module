<?php

declare(strict_types=1);

namespace Tests\Unit\Application;

use App\Application\Auth\Commands\LoginUserCommand;
use App\Application\Auth\UseCases\LoginUserUseCase;
use App\Domain\Auth\Contracts\PasswordHasherInterface;
use App\Domain\Auth\Contracts\UserRepositoryInterface;
use App\Domain\Auth\Entities\User;
use App\Domain\Auth\Exceptions\InvalidCredentialsException;
use App\Domain\Auth\ValueObjects\Email;
use App\Domain\Auth\ValueObjects\HashedPassword;
use App\Domain\Auth\ValueObjects\UserId;
use DateTimeImmutable;
use Mockery;
use Mockery\CompositeExpectation;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

final class LoginUserUseCaseTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_logs_in_a_user_with_valid_credentials(): void
    {
        ['repo' => $repo, 'hasher' => $hasher, 'useCase' => $useCase] = $this->makeUseCase();
        $user = new User(
            new UserId('9f6b3b7e-8d42-4a86-9d0c-c2d1966a87de'),
            new Email('alice@example.com'),
            'Alice',
            new HashedPassword('hashed'),
            new DateTimeImmutable,
        );

        $this->expectMock($repo, 'findByEmail')->andReturn($user);
        $this->expectMock($hasher, 'verify')->andReturn(true);

        $response = $useCase->execute(new LoginUserCommand('alice@example.com', 'secret'));

        $this->assertSame($user, $response->user);
        $this->assertSame(64, strlen($response->sessionToken));
    }

    public function test_always_verifies_a_password_even_when_the_user_is_missing(): void
    {
        ['repo' => $repo, 'hasher' => $hasher, 'useCase' => $useCase] = $this->makeUseCase();

        $this->expectMock($repo, 'findByEmail')->andReturn(null);
        $this->expectMock($hasher, 'hash')->andReturn(new HashedPassword('dummy'));
        $this->expectMock($hasher, 'verify')->andReturn(false);

        $this->expectException(InvalidCredentialsException::class);

        $useCase->execute(new LoginUserCommand('nobody@example.com', 'secret'));
    }

    /**
     * @return array{repo: UserRepositoryInterface&MockInterface, hasher: PasswordHasherInterface&MockInterface, useCase: LoginUserUseCase}
     */
    private function makeUseCase(): array
    {
        /** @var UserRepositoryInterface&MockInterface $repo */
        $repo = Mockery::mock(UserRepositoryInterface::class);
        /** @var PasswordHasherInterface&MockInterface $hasher */
        $hasher = Mockery::mock(PasswordHasherInterface::class);

        return [
            'repo' => $repo,
            'hasher' => $hasher,
            'useCase' => new LoginUserUseCase($repo, $hasher),
        ];
    }

    private function expectMock(MockInterface $mock, string $method): CompositeExpectation
    {
        /** @var CompositeExpectation $expectation */
        $expectation = $mock->expects($method);

        return $expectation;
    }
}
