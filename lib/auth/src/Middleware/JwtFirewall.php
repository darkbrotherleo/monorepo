<?php
declare(strict_types=1);

namespace Monorepo\Auth\Middleware;

use Tuupola\Middleware\JwtAuthentication;

/**
 * Wrapper cho tuupola/slim-jwt-auth — path rules + cookie + HTTPS enforce
 * Alternative: cube43/slim-jwt-auth (hydrator + firewall tách lớp) thì dùng tương tự.
 */
class JwtFirewall
{
    public static function create(string $secret): JwtAuthentication
    {
        return new JwtAuthentication([
            'secret' => $secret,
            'attribute' => 'jwt',
            'path' => ['/api'],
            'ignore' => ['/api/login', '/api/register', '/health', '/api/v1/ping'],
            'secure' => (($_ENV['APP_ENV'] ?? 'development') === 'production'),
            'relaxed' => ['localhost', '127.0.0.1'],
            'error' => function ($response, $args) {
                $data = ['error' => 'unauthorized', 'message' => $args['message'] ?? 'Invalid token'];
                $response->getBody()->write(json_encode($data));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
            },
            'before' => function ($request, $args) {
                // Re-check lưu ý: middleware không phải sole auth — controller phải verify lại role
                return $request;
            },
        ]);
    }
}
