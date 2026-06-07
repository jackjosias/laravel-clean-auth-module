<?php

declare(strict_types=1);

return [
    'default' => env('CACHE_STORE', 'file'),
    'stores' => [
        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],
        'database' => [
            'driver' => 'database',
            'connection' => env('DB_CONNECTION'),
            'table' => env('CACHE_TABLE', 'cache'),
            'lock_connection' => env('DB_CONNECTION'),
            'lock_table' => env('CACHE_LOCK_TABLE', 'cache_locks'),
        ],
        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
            'lock_path' => storage_path('framework/cache/data'),
        ],
    ],
    'prefix' => env('CACHE_PREFIX', 'clean_auth_cache'),
];
