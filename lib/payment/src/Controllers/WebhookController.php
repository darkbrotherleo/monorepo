<?php
declare(strict_types=1);

namespace Monorepo\Payment\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Monorepo\Payment\Gateways\GatewayInterface;

class WebhookController
{
    public function __construct(private \PDO $pdo, private GatewayInterface $gateway, private string $webhookSecret) {}

    public function handle(Request $req, Response $res): Response
    {
        $rawBody = file_get_contents('php://input');
        $sig = $req->getHeaderLine('Stripe-Signature') ?: $req->getHeaderLine('X-Vnp-SecureHash') ?: '';
        if (!$this->gateway->verifyWebhook($rawBody, $sig, $this->webhookSecret)) {
            $res->getBody()->write(json_encode(['error' => 'invalid_signature']));
            return $res->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
        // Update transaction — verified
        $data = json_decode($rawBody, true) ?? [];
        $stmt = $this->pdo->prepare('INSERT INTO transactions (order_id, status, payload) VALUES (?, ?, ?)');
        $stmt->execute([$data['order_id'] ?? 0, 'paid', $rawBody]);
        $res->getBody()->write(json_encode(['ok' => true]));
        return $res->withHeader('Content-Type', 'application/json');
    }
}
