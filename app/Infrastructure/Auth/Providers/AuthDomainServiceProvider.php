<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Providers;

use App\Domain\Auth\Contracts\PasswordHasherInterface;
use App\Domain\Auth\Contracts\UserRepositoryInterface;
use App\Domain\Auth\Services\PasswordStrengthService;
use App\Infrastructure\Auth\Repositories\EloquentUserRepository;
use App\Infrastructure\Auth\Security\ArgonPasswordHasher;
use Illuminate\Support\ServiceProvider;

final class AuthDomainServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(PasswordHasherInterface::class, ArgonPasswordHasher::class);
        $this->app->singleton(PasswordStrengthService::class);
    }
}
