<?php
declare(strict_types=1);

namespace Monorepo\Payment\Gateways;

interface GatewayInterface
{
    public function createPaymentUrl(int $amount, string $orderId, string $returnUrl): string;
    public function verifyWebhook(string $rawBody, string $signature, string $secret): bool;
}
