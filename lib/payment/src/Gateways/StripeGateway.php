<?php
declare(strict_types=1);

namespace Monorepo\Payment\Gateways;

class StripeGateway implements GatewayInterface
{
    public function __construct(private string $secret) {}

    public function createPaymentUrl(int $amount, string $orderId, string $returnUrl): string
    {
        // Never trust unit_amount from client — lookup server-side
        // Placeholder: call \Stripe\StripeClient with Price ID lookup
        return "https://checkout.stripe.com/pay/{$orderId}";
    }

    public function verifyWebhook(string $rawBody, string $signature, string $secret): bool
    {
        // Must use php://input raw body + Stripe\Webhook::constructEvent
        try {
            \Stripe\Webhook::constructEvent($rawBody, $signature, $secret);
            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
