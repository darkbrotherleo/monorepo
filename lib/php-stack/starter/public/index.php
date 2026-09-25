<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Psr7\Factory\ResponseFactory;

require __DIR__ . '/../vendor/autoload.php';

// Load .env
if (file_exists(__DIR__ . '/../.env')) {
    (Dotenv\Dotenv::createImmutable(__DIR__ . '/..'))->load();
}

// Build container
$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/../config/settings.php');
$builder->addDefinitions(__DIR__ . '/../config/container.php');
$builder->addDefinitions(__DIR__ . '/../config/database.php');
$container = $builder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();

// PSR-15: ResponseFactory binding for middleware (Slim docs)
$container->set(ResponseFactoryInterface::class, fn() => new ResponseFactory());

// Middleware LIFO: last added = first executed
// Order: ErrorMiddleware (outermost) -> Routing -> BodyParsing -> app
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(
    (bool)($_ENV['APP_DEBUG'] ?? true),
    true,
    true
);

// Custom app middleware (session, etc.)
(require __DIR__ . '/../config/middleware.php')($app);

// Routes
(require __DIR__ . '/../config/routes.php')($app);

$app->run();
