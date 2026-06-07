<?php

declare(strict_types=1);

namespace App\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class RequireAuthSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('auth.user_id')) {
            return redirect()->route('login')->withErrors(['session' => 'Vous devez etre connecte.']);
        }

        return $next($request);
    }
}
