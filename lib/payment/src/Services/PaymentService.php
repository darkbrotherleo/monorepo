<?php
declare(strict_types=1);

namespace Monorepo\Payment\Services;

use Monorepo\Payment\Gateways\GatewayInterface;

class PaymentService
{
    public function __construct(private \PDO $pdo, private GatewayInterface $gateway) {}

    public function createOrder(int $userId, int $amount, string $gateway, string $idempotencyKey): array
    {
        // Idempotency: SELECT first
        $stmt = $this->pdo->prepare('SELECT id FROM orders WHERE idempotency_key = ?');
        $stmt->execute([$idempotencyKey]);
        if ($row = $stmt->fetch()) return ['id' => $row['id'], 'duplicate' => true];

        $stmt = $this->pdo->prepare('INSERT INTO orders (user_id, amount, gateway, idempotency_key) VALUES (?, ?, ?, ?)');
        $stmt->execute([$userId, $amount, $gateway, $idempotencyKey]);
        return ['id' => $this->pdo->lastInsertId(), 'duplicate' => false];
    }
}
