<?php
declare(strict_types=1);

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

return function (App $app) {
    // Rate-limit (PSR-15) — Redis khi có, file fallback. Mount trước security headers để 429 vẫn có headers.
    // Dùng nikolaposa/rate-limit ideas; sim-soft/slim cũng tương tự RateLimitRedisStorage.
    $redis = null;
    if (extension_loaded('redis') || class_exists(\Predis\Client::class)) {
        try {
            if (class_exists(\Predis\Client::class) && !empty($_ENV['REDIS_HOST'])) {
                $redis = new \Predis\Client(['host' => $_ENV['REDIS_HOST'], 'port' => (int)($_ENV['REDIS_PORT'] ?? 6379)]);
                $redis->ping();
            }
        } catch (\Throwable $e) { $redis = null; }
    }
    // Chỉ rate-limit /api/* — whitelist /health, /api/v1/ping
    $app->add(function (\Psr\Http\Message\ServerRequestInterface $req, \Psr\Http\Server\RequestHandlerInterface $handler) use ($redis) {
        $path = $req->getUri()->getPath();
        if (str_starts_with($path, '/api/') && $path !== '/api/v1/ping' && $path !== '/health') {
            $mw = new \App\Application\Http\Middleware\RateLimitMiddleware(100, 60, $redis, 'myapp:rate:');
            return $mw->process($req, $handler);
        }
        return $handler->handle($req);
    });

    // Security headers (OWASP WSTG CONF)
    $app->add(function (Request $req, Handler $handler): Response {
        $res = $handler->handle($req);
        return $res
            ->withHeader('X-Frame-Options', 'DENY')
            ->withHeader('X-Content-Type-Options', 'nosniff')
            ->withHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->withHeader('Content-Security-Policy', "default-src 'self'")
            ->withHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
    });

    // Correlation ID
    $app->add(function (Request $req, Handler $handler): Response {
        $cid = bin2hex(random_bytes(8));
        $req = $req->withAttribute('correlation_id', $cid);
        $res = $handler->handle($req);
        return $res->withHeader('X-Correlation-ID', $cid);
    });
};
