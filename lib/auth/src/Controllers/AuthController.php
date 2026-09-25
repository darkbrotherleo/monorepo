<?php
declare(strict_types=1);

namespace Monorepo\Auth\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Monorepo\Auth\Services\JwtService;

class AuthController
{
    public function __construct(private \PDO $pdo, private JwtService $jwt) {}

    public function register(Request $req, Response $res): Response
    {
        $data = (array)$req->getParsedBody();
        // Whitelist + Respect\Validation (security-audit)
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
            $res->getBody()->write(json_encode(['error' => 'validation_failed']));
            return $res->withStatus(422)->withHeader('Content-Type', 'application/json');
        }
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare('INSERT INTO users (email, password) VALUES (?, ?)');
        $stmt->execute([$email, $hash]); // PDO prepare — no concat
        $res->getBody()->write(json_encode(['id' => $this->pdo->lastInsertId()]));
        return $res->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    public function login(Request $req, Response $res): Response
    {
        $data = (array)$req->getParsedBody();
        $stmt = $this->pdo->prepare('SELECT id, password, role FROM users WHERE email = ?');
        $stmt->execute([$data['email'] ?? '']);
        $user = $stmt->fetch();
        if (!$user || !password_verify($data['password'] ?? '', $user['password'])) {
            $res->getBody()->write(json_encode(['error' => 'invalid_credentials']));
            return $res->withStatus(401)->withHeader('Content-Type', 'application/json');
        }
        $token = $this->jwt->issue(['sub' => $user['id'], 'role' => $user['role']]);
        $res->getBody()->write(json_encode(['token' => $token]));
        return $res->withHeader('Content-Type', 'application/json');
    }
}
