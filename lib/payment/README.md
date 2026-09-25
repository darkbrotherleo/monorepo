# lib/payment — Thanh Toán

Module tái sử dụng cho thanh toán. Mẫu khởi tạo.

## Tính năng dự kiến
- Tạo link thanh toán VNPay / Momo / Stripe
- Xử lý webhook / IPN
- Quản lý trạng thái Transaction
- Hoàn tiền (refund)

## Sử dụng (ví dụ)

```js
const PaymentService = require('./src/services/PaymentService');
const url = await PaymentService.createPaymentUrl({ gateway: 'vnpay', amount: 100000, orderId: 'ORD123' });
```

## Tích hợp
Xem `AGENT.md` trong cùng thư mục.

---
*Teams: chờ repo nguồn để tích hợp.*
