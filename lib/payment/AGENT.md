# AGENT.md — lib/payment

## Mục Đích
Module thanh toán tái sử dụng: tích hợp gateway (VNPay, Momo, Stripe, PayPal), xử lý webhook, quản lý đơn hàng & trạng thái thanh toán.

## Kiến Trúc Dự Kiến
```
lib/payment/
├── AGENT.md
├── README.md
├── src/
│   ├── gateways/     # VNPay, Momo, Stripe adapter (strategy pattern)
│   ├── services/     # PaymentService
│   ├── controllers/  # PaymentController
│   ├── models/       # Order, Transaction
│   └── webhooks/     # Xử lý callback từ gateway
└── config/
```

## Cách Tích Hợp Vào Dự Án
1. Copy `lib/payment/src/` vào dự án.
2. Cài SDK gateway tương ứng.
3. Config key/secret trong `.env`: `VNP_TMNCODE`, `MOMO_PARTNER_CODE`, `STRIPE_SECRET_KEY`.
4. Đăng ký route webhook để nhận callback.

## Dependencies / Yêu Cầu
- Database bảng `orders`, `transactions` (migration `20260923000002_create_orders.php`)
- HTTPS cho webhook
- Composer: `omnipay/omnipay ^3.0` + `phpviet/omnipay-vnpay ^1.0` (hoặc `jackiedo/omnipay-vnpay`), `stripe/stripe-php ^10.0` cho Stripe

## Lưu Ý
- Luôn verify chữ ký webhook (Omnipay `completePurchase` đã sort + SecureHash, thay hand-rolled `hash('sha256', secret+query)`).
- Không log raw card data. Dùng idempotency key.
- Rate-limit IPN endpoint (tuanta1992/laravel-vnpay checklist), check `VNPAY_HASH_SECRET` và `php artisan vnpay:status` tương tự.

## Trạng Thái
🟡 Mẫu khởi tạo — chờ tích hợp repo thực tế.

---
*Cập nhật: 2026-09-23*
