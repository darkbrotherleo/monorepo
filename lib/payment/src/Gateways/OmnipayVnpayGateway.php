<?php
declare(strict_types=1);

namespace Monorepo\Payment\Gateways;

use Omnipay\Omnipay;

/**
 * Wrapper Omnipay VNPay — sort keys + SecureHash chuẩn, thay hand-rolled
 * Dựa trên phpviet/omnipay-vnpay + JackieDo/Omnipay-VnPay (đã tìm GitHub)
 */
class OmnipayVnpayGateway implements GatewayInterface
{
    private $gateway;

    public function __construct(string $tmnCode, string $hashSecret)
    {
        $gw = Omnipay::create('VNPay');
        $gw->initialize(['vnp_TmnCode' => $tmnCode, 'vnp_HashSecret' => $hashSecret]);
        $this->gateway = $gw;
    }

    public function createPaymentUrl(int $amount, string $orderId, string $returnUrl): string
    {
        // Omnipay PurchaseRequest đã sort + hash chuẩn
        $req = $this->gateway->purchase([
            'vnp_TxnRef' => $orderId,
            'vnp_Amount' => $amount,
            'vnp_ReturnUrl' => $returnUrl,
        ]);
        $res = $req->send();
        return $res->getRedirectUrl() ?? '';
    }

    public function verifyWebhook(string $rawBody, string $signature, string $secret): bool
    {
        parse_str($rawBody, $data);
        // Omnipay completePurchase sẽ validate SecureHash
        try {
            $req = $this->gateway->completePurchase(['vnp_SecureHash' => $signature] + $data);
            $res = $req->send();
            return $res->isSuccessful();
        } catch (\Throwable) {
            return false;
        }
    }

    /** QueryDR — check transaction status sau IPN */
    public function fetchTransaction(string $txnRef): array
    {
        $req = $this->gateway->fetchTransaction(['vnp_TxnRef' => $txnRef]);
        $res = $req->send();
        return $res->getData() ?? [];
    }
}
