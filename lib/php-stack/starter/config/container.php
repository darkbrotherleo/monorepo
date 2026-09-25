<?php
declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\JsonFormatter;
use Psr\Log\LoggerInterface;
use League\Plates\Engine;

return [
    LoggerInterface::class => function (ContainerInterface $c) {
        $settings = $c->get('settings')['logger'];
        $logger = new Logger($settings['name']);
        $handler = new StreamHandler($settings['path'], $settings['level']);
        $handler->setFormatter(new JsonFormatter());
        $logger->pushHandler($handler);
        return $logger;
    },
    Engine::class => function () {
        return new Engine(__DIR__ . '/../templates');
    },
    // Plates alias 'view'
    'view' => fn(ContainerInterface $c) => $c->get(Engine::class),
];
