<?php
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
if (file_exists(__DIR__ . '/.env')) $dotenv->load();

return [
    'paths' => ['migrations' => '%%PHINX_CONFIG_DIR%%/migrations', 'seeds' => '%%PHINX_CONFIG_DIR%%/seeds'],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'development' => [
            'name' => $_ENV['DB_DATABASE'] ?? 'ten_du_an',
            'connection' => new PDO(
                $_ENV['DB_DSN'] ?? 'mysql:host=127.0.0.1;dbname=ten_du_an;charset=utf8mb4',
                $_ENV['DB_USERNAME'] ?? 'root',
                $_ENV['DB_PASSWORD'] ?? ''
            ),
        ],
        'testing' => [
            'name' => ($_ENV['DB_DATABASE'] ?? 'ten_du_an') . '_test',
            'connection' => new PDO(
                $_ENV['DB_DSN'] ?? 'mysql:host=127.0.0.1;dbname=ten_du_an_test;charset=utf8mb4',
                $_ENV['DB_USERNAME'] ?? 'root',
                $_ENV['DB_PASSWORD'] ?? ''
            ),
        ],
    ],
];
