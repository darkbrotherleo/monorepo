# slim-supplement.md — Bù khoảng trống Cheatsheets cho Slim 4 (PHP-DI + Plates)

> Upstream `awesome-cheatsheets` không có Slim. File này bổ sung cho `lib/php-stack`.

## 1. Tạo App (Slim 4 + PHP-DI)
```php
// public/index.php
use DI\Container;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->get('/hello/{name}', fn($req,$res,$args) => $res->getBody()->write("Hello {$args['name']}"));
$app->run();
```

## 2. Container (PHP-DI)
```php
// config/container.php
return [
  PDO::class => fn() => new PDO($_ENV['DB_DSN']),
  'view' => fn() => League\Plates\Engine::create(__DIR__.'/../templates'),
];
```

## 3. Plates Layout
```php
// templates/layout.php
<?=$this->section('content')?>
// templates/hello.php
<?php $this->layout('layout') ?>Hello <?=$this->e($name)?>
```

## 4. Middleware (PSR-15)
```php
$app->add(function($req,$handler){
  $res = $handler->handle($req);
  return $res->withHeader('X-Frame-Options','DENY');
});
```

## 5. Validation (Respect)
```php
use Respect\Validation\Validator as v;
v::stringType()->length(1,100)->email()->assert($data['email']);
```

## 6. Liên quan
- `lib/php-stack/composer.json` (full) / `composer-slim.json` (minimum)
- `shared/cheatsheets/php.php` (generic PHP) + `mysql.sh` (DDL) + `nginx.sh` (headers)
