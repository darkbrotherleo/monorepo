<?php
declare(strict_types=1);

namespace Monorepo\Payment\Gateways;

class VnpayGateway implements GatewayInterface
{
    public function __construct(private string $tmnCode, private string $hashSecret) {}

    public function createPaymentUrl(int $amount, string $orderId, string $returnUrl): string
    {
        $data = ['vnp_Amount' => $amount * 100, 'vnp_TxnRef' => $orderId, 'vnp_ReturnUrl' => $returnUrl];
        $hash = hash_hmac('sha512', http_build_query($data), $this->hashSecret);
        return "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?" . http_build_query($data) . "&vnp_SecureHash=$hash";
    }

    public function verifyWebhook(string $rawBody, string $signature, string $secret): bool
    {
        parse_str($rawBody, $data);
        $hash = $data['vnp_SecureHash'] ?? '';
        unset($data['vnp_SecureHash']);
        $calc = hash_hmac('sha512', http_build_query($data), $secret);
        return hash_equals($calc, $hash);
    }
}
