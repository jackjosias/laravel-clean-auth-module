<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers\Auth;

use App\Application\Auth\UseCases\LogoutUserUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class LogoutController extends Controller
{
    public function __construct(
        private readonly LogoutUserUseCase $logoutUseCase,
    ) {}

    public function handle(Request $request): RedirectResponse
    {
        $this->logoutUseCase->execute();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Deconnexion reussie.');
    }
}
