<?php

declare(strict_types=1);

namespace App\Presentation\Http\Security;

use Illuminate\Http\Request;

final class SessionFingerprint
{
    public static function fromRequest(Request $request): string
    {
        return hash('sha256', ($request->ip() ?? '').($request->userAgent() ?? ''));
    }
}
