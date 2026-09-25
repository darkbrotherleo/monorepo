# lib/notification — Thông Báo

Module tái sử dụng cho thông báo đa kênh. Mẫu khởi tạo.

## Tính năng dự kiến
- Email (SendGrid, SMTP)
- SMS (Twilio, Vonage)
- Push (FCM, APNs)
- Template + Queue

## Sử dụng (ví dụ)

```js
const NotificationService = require('./src/services/NotificationService');
await NotificationService.send({
  channel: 'email',
  to: 'user@example.com',
  template: 'welcome',
  data: { name: 'John' }
});
```

## Tích hợp
Xem `AGENT.md` trong cùng thư mục.

---
*Teams: chờ repo nguồn để tích hợp.*
