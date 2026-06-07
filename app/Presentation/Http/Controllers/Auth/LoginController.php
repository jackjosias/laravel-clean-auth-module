<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Auth;

use App\Application\Auth\Commands\LoginUserCommand;
use App\Application\Auth\UseCases\LoginUserUseCase;
use App\Domain\Auth\Exceptions\InvalidCredentialsException;
use App\Presentation\Http\Requests\Auth\LoginRequest;
use App\Presentation\Http\Security\SessionFingerprint;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;

final class LoginController extends Controller
{
    public function __construct(
        private readonly LoginUserUseCase $loginUseCase,
    ) {}

    public function show(): View
    {
        return view('auth.login');
    }

    public function handle(LoginRequest $request): RedirectResponse
    {
        try {
            $response = $this->loginUseCase->execute($this->command($request));
            $request->session()->regenerate();
            $request->session()->put('auth.user_id', $response->user->getId()->value);
            $request->session()->put('auth.fingerprint', SessionFingerprint::fromRequest($request));

            return redirect()->intended(route('dashboard'));
        } catch (InvalidCredentialsException) {
            usleep(200_000);

            return back()->withErrors(['credentials' => 'Identifiants incorrects.'])
                ->withInput(['email' => $request->input('email')]);
        }
    }

    private function command(LoginRequest $request): LoginUserCommand
    {
        return new LoginUserCommand(
            email: (string) $request->validated('email'),
            plainPassword: (string) $request->validated('password'),
            remember: (bool) $request->validated('remember', false),
        );
    }
}
