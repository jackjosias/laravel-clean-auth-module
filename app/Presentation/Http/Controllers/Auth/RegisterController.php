<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Auth;

use App\Application\Auth\Commands\RegisterUserCommand;
use App\Application\Auth\UseCases\RegisterUserUseCase;
use App\Domain\Auth\Exceptions\UserAlreadyExistsException;
use App\Domain\Auth\Exceptions\WeakPasswordException;
use App\Presentation\Http\Requests\Auth\RegisterRequest;
use App\Presentation\Http\Security\SessionFingerprint;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;

final class RegisterController extends Controller
{
    public function __construct(
        private readonly RegisterUserUseCase $registerUseCase,
    ) {}

    public function show(): View
    {
        return view('auth.register');
    }

    public function handle(RegisterRequest $request): RedirectResponse
    {
        try {
            $response = $this->registerUseCase->execute($this->command($request));
            $request->session()->regenerate();
            $request->session()->put('auth.user_id', $response->user->getId()->value);
            $request->session()->put('auth.fingerprint', SessionFingerprint::fromRequest($request));

            return redirect()->route('dashboard')->with('success', $response->message);
        } catch (UserAlreadyExistsException $exception) {
            return back()->withErrors(['email' => $exception->getMessage()])->withInput();
        } catch (WeakPasswordException $exception) {
            return back()->withErrors(['password' => $exception->getMessage()])->withInput();
        }
    }

    private function command(RegisterRequest $request): RegisterUserCommand
    {
        return new RegisterUserCommand(
            name: (string) $request->validated('name'),
            email: (string) $request->validated('email'),
            plainPassword: (string) $request->validated('password'),
        );
    }
}
