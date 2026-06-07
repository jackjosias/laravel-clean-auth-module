<?php

declare(strict_types=1);

namespace Tests\Unit\Application;

use App\Application\Auth\Commands\RegisterUserCommand;
use App\Application\Auth\UseCases\RegisterUserUseCase;
use App\Domain\Auth\Contracts\PasswordHasherInterface;
use App\Domain\Auth\Contracts\UserRepositoryInterface;
use App\Domain\Auth\Entities\User;
use App\Domain\Auth\Exceptions\UserAlreadyExistsException;
use App\Domain\Auth\Exceptions\WeakPasswordException;
use App\Domain\Auth\Services\PasswordStrengthService;
use App\Domain\Auth\ValueObjects\HashedPassword;
use Mockery;
use Mockery\CompositeExpectation;
use Mockery\Expectation;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

final class RegisterUserUseCaseTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_registers_a_user_with_a_unique_email_and_strong_password(): void
    {
        ['repo' => $repo, 'hasher' => $hasher, 'useCase' => $useCase] = $this->makeUseCase();

        $this->expectMock($repo, 'existsByEmail')->andReturn(false);
        $this->expectMock($hasher, 'hash')->andReturn(new HashedPassword('hashed'));
        /** @var Expectation $saveExpectation */
        $saveExpectation = $repo->shouldReceive('save');
        $saveExpectation->once()->with(Mockery::type(User::class));

        $response = $useCase->execute(
            new RegisterUserCommand('Alice', 'alice@example.com', 'Str0ng!Pass#2026')
        );

        $this->assertSame('Alice', $response->user->getName());
    }

    public function test_rejects_an_already_registered_email(): void
    {
        ['repo' => $repo, 'useCase' => $useCase] = $this->makeUseCase();

        $this->expectMock($repo, 'existsByEmail')->andReturn(true);

        $this->expectException(UserAlreadyExistsException::class);

        $useCase->execute(new RegisterUserCommand('Bob', 'bob@example.com', 'Str0ng!Pass#2026'));
    }

    public function test_rejects_a_weak_password(): void
    {
        ['repo' => $repo, 'useCase' => $useCase] = $this->makeUseCase();

        $this->expectMock($repo, 'existsByEmail')->andReturn(false);

        $this->expectException(WeakPasswordException::class);

        $useCase->execute(new RegisterUserCommand('Charlie', 'charlie@example.com', 'weak'));
    }

    /**
     * @return array{repo: UserRepositoryInterface&MockInterface, hasher: PasswordHasherInterface&MockInterface, useCase: RegisterUserUseCase}
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
            'useCase' => new RegisterUserUseCase(
                $repo,
                $hasher,
                new PasswordStrengthService,
            ),
        ];
    }

    private function expectMock(MockInterface $mock, string $method): CompositeExpectation
    {
        /** @var CompositeExpectation $expectation */
        $expectation = $mock->expects($method);

        return $expectation;
    }
}
