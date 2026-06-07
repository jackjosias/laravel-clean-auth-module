<?php

declare(strict_types=1);

return [
    'driver' => env('SESSION_DRIVER', 'database'),
    'lifetime' => (int) env('SESSION_LIFETIME', 120),
    'expire_on_close' => false,
    'encrypt' => (bool) env('SESSION_ENCRYPT', true),
    'files' => storage_path('framework/sessions'),
    'connection' => env('SESSION_CONNECTION'),
    'table' => env('SESSION_TABLE', 'sessions'),
    'store' => env('SESSION_STORE'),
    'lottery' => [2, 100],
    'cookie' => env('SESSION_COOKIE', 'laravel_clean_auth_session'),
    'path' => env('SESSION_PATH', '/'),
    'domain' => env('SESSION_DOMAIN'),
    'secure' => (bool) env('SESSION_SECURE_COOKIE', true),
    'http_only' => true,
    'same_site' => env('SESSION_SAME_SITE', 'strict'),
    'partitioned' => false,
];
