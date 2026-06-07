<?php

declare(strict_types=1);

namespace App\Presentation\Http\Middleware;

use App\Presentation\Http\Security\SessionFingerprint;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ValidateSessionFingerprint
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = $request->session()->get('auth.fingerprint');
        $current = SessionFingerprint::fromRequest($request);

        if (! is_string($expected) || ! hash_equals($expected, $current)) {
            $request->session()->invalidate();

            return redirect()->route('login')->withErrors(['session' => 'Session invalide. Reconnectez-vous.']);
        }

        return $next($request);
    }
}
