<?php
declare(strict_types=1);

namespace Monorepo\Auth\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Monorepo\Auth\Services\JwtService;

class AuthenticateMiddleware implements MiddlewareInterface
{
    public function __construct(private JwtService $jwt) {}

    public function process(Request $req, Handler $handler): Response
    {
        $header = $req->getHeaderLine('Authorization');
        if (!str_starts_with($header, 'Bearer ')) {
            $res = new \Slim\Psr7\Response();
            $res->getBody()->write(json_encode(['error' => 'unauthorized']));
            return $res->withStatus(401)->withHeader('Content-Type', 'application/json');
        }
        $token = substr($header, 7);
        try {
            $claims = $this->jwt->verify($token);
        } catch (\Throwable $e) {
            $res = new \Slim\Psr7\Response();
            $res->getBody()->write(json_encode(['error' => 'invalid_token']));
            return $res->withStatus(401)->withHeader('Content-Type', 'application/json');
        }
        // Re-check must happen in controller/service too — middleware not sole auth (CVE-2025-29927 analog)
        $req = $req->withAttribute('jwt', $claims);
        return $handler->handle($req);
    }
}
