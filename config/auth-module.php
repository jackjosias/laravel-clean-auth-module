<?php

declare(strict_types=1);

return [
    'password' => [
        'min_length' => 10,
        'strong_length' => 14,
        'min_score' => 3,
    ],
    'argon2id' => [
        'memory_cost' => 65536,
        'time_cost' => 4,
        'threads' => 1,
    ],
    'rate_limit' => [
        'attempts' => 5,
        'decay_minutes' => 1,
    ],
];
