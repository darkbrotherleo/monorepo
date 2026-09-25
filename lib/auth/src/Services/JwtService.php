<?php
declare(strict_types=1);

namespace Monorepo\Auth\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * JWT service — verify=true, reject alg:none (security-audit)
 */
class JwtService
{
    public function __construct(private string $secret, private int $expires = 3600) {}

    public function issue(array $claims): string
    {
        $now = time();
        $payload = array_merge($claims, ['iat' => $now, 'exp' => $now + $this->expires]);
        return JWT::encode($payload, $this->secret, 'HS256');
    }

    /** @throws \Exception on invalid/expired */
    public function verify(string $token): object
    {
        // Explicitly only HS256 — alg:none rejected by library when Key is provided
        return JWT::decode($token, new Key($this->secret, 'HS256'));
    }
}
