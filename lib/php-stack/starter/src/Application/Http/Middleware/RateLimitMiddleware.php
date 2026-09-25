<?php
declare(strict_types=1);

namespace App\Application\Http\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Predis\Client as Redis;
use Slim\Psr7\Response as SlimResponse;

/**
 * PSR-15 rate-limit: IP-based, Redis khi có, file fallback single-server
 * Dựa trên nikolaposa/rate-limit + wellingguzman/rate-limit patterns đã tìm GitHub
 */
class RateLimitMiddleware implements MiddlewareInterface
{
    public function __construct(
        private int $maxRequests = 100,
        private int $windowSeconds = 60,
        private ?Redis $redis = null,
        private string $prefix = 'rate:',
    ) {}

    public function process(Request $req, Handler $handler): Response
    {
        $ip = $req->getServerParams()['REMOTE_ADDR'] ?? 'unknown';
        $key = $this->prefix . $ip;

        if ($this->redis) {
            $count = (int)$this->redis->incr($key);
            if ($count === 1) $this->redis->expire($key, $this->windowSeconds);
            if ($count > $this->maxRequests) return $this->tooMany($count);
            $remaining = max(0, $this->maxRequests - $count);
        } else {
            // File fallback — single-server only
            $file = sys_get_temp_dir() . '/' . md5($key) . '.rl';
            $data = is_file($file) ? json_decode(file_get_contents($file) ?: '', true) : null;
            $now = time();
            if (!$data || $now - $data['start'] >= $this->windowSeconds) {
                $data = ['start' => $now, 'count' => 1];
            } else {
                $data['count']++;
            }
            file_put_contents($file, json_encode($data));
            if ($data['count'] > $this->maxRequests) return $this->tooMany($data['count']);
            $remaining = max(0, $this->maxRequests - $data['count']);
        }

        $res = $handler->handle($req);
        return $res->withHeader('X-RateLimit-Remaining', (string)$remaining)
                   ->withHeader('X-RateLimit-Limit', (string)$this->maxRequests);
    }

    private function tooMany(int $count): Response
    {
        $res = new SlimResponse(429);
        $res->getBody()->write(json_encode(['error' => 'rate_limit_exceeded', 'count' => $count]));
        return $res->withHeader('Content-Type', 'application/json')
                   ->withHeader('Retry-After', (string)$this->windowSeconds);
    }
}
