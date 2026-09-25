<?php
declare(strict_types=1);

return [
    'settings' => [
        'displayErrorDetails' => (bool)($_ENV['APP_DEBUG'] ?? true),
        'logError' => true,
        'logErrorDetails' => true,
        'logger' => [
            'name' => 'slim',
            'path' => __DIR__ . '/../var/log/app.log',
            'level' => Monolog\Logger::DEBUG,
        ],
        'db' => [
            'dsn' => $_ENV['DB_DSN'] ?? 'mysql:host=127.0.0.1;dbname=ten_du_an;charset=utf8mb4',
            'user' => $_ENV['DB_USERNAME'] ?? 'root',
            'pass' => $_ENV['DB_PASSWORD'] ?? '',
        ],
        'jwt' => [
            'secret' => $_ENV['JWT_SECRET'] ?? 'change-me-32chars-min',
            'expires' => (int)($_ENV['JWT_EXPIRES_IN'] ?? 3600),
        ],
    ],
];
