# AGENT.md — lib/notification

## Mục Đích
Module thông báo tái sử dụng: gửi Email, SMS, Push notification, hỗ trợ queue & template.

## Kiến Trúc Dự Kiến
```
lib/notification/
├── AGENT.md
├── README.md
├── src/
│   ├── channels/     # email, sms, push (strategy)
│   ├── services/     # NotificationService
│   ├── templates/    # Template engine
│   ├── queue/        # Job queue (Bull, RabbitMQ)
│   └── providers/    # SendGrid, Twilio, FCM adapter
└── config/
```

## Cách Tích Hợp Vào Dự Án
1. Copy `lib/notification/src/` vào dự án.
2. Cài provider SDK: `nodemailer`, `twilio`, `firebase-admin` (tùy nhu cầu).
3. Config trong `.env`: `MAIL_HOST`, `TWILIO_SID`, `FCM_SERVER_KEY`.
4. Gọi `NotificationService.send({ channel, to, template, data })`.

## Dependencies / Yêu Cầu
- Queue (Redis/Bull) nếu gửi async
- Tài khoản provider (SendGrid, Twilio, Firebase)

## Lưu Ý
- Luôn gửi async qua queue để không block request chính.
- Template nên tách riêng, hỗ trợ i18n.
- Log đầy đủ để trace khi gửi lỗi.

## Trạng Thái
🟡 Mẫu khởi tạo — chờ tích hợp repo thực tế.

---
*Cập nhật: 2026-09-23*
