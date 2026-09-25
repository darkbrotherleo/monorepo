<?php
declare(strict_types=1);

use Psr\Container\ContainerInterface;

return [
    PDO::class => function (ContainerInterface $c) {
        $db = $c->get('settings')['db'];
        $pdo = new PDO($db['dsn'], $db['user'], $db['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return $pdo;
    },
];
