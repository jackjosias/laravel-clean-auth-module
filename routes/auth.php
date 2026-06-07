<?php

declare(strict_types=1);

use App\Presentation\Http\Controllers\Auth\LoginController;
use App\Presentation\Http\Controllers\Auth\LogoutController;
use App\Presentation\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest.session')->group(function (): void {
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'handle'])
        ->middleware('throttle:5,1');

    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'handle'])
        ->middleware('throttle:5,1');
});

Route::middleware(['auth.session', 'auth.fingerprint'])->group(function (): void {
    Route::post('/logout', [LogoutController::class, 'handle'])->name('logout');
    Route::get('/dashboard', fn () => view('auth.dashboard'))->name('dashboard');
});
