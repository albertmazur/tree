<?php

declare(strict_types=1);

return [
    'db' => [
        'host' => getenv('DB_HOST') ?? 'localhost',
        'database' => getenv('DB_NAME') ?? 'categories',
        'table' => getenv('DB_TABLE') ?? 'tree',
        'user' => getenv('DB_USER') ?? 'root',
        'password' => getenv('DB_PASS') ?? 'Password'
    ]
];