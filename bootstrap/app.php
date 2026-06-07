<?php

declare(strict_types=1);

use App\Presentation\Http\Middleware\RedirectIfAuthenticatedSession;
use App\Presentation\Http\Middleware\RequireAuthSession;
use App\Presentation\Http\Middleware\ValidateSessionFingerprint;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.session' => RequireAuthSession::class,
            'auth.fingerprint' => ValidateSessionFingerprint::class,
            'guest.session' => RedirectIfAuthenticatedSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
