# lib/auth — Xác Thực & Phân Quyền

Module tái sử dụng cho chức năng auth. Hiện là mẫu khởi tạo, chờ tích hợp code thực tế.

## Tính năng dự kiến
- Đăng ký / Đăng nhập (email + password)
- JWT access + refresh token
- OAuth (Google, Facebook)
- Middleware `authenticate` / `authorize(role)`
- Quên mật khẩu / Reset password

## Sử dụng (ví dụ Node.js/Express)

```js
const { authenticate, authorize } = require('./src/middleware/auth');
app.get('/api/profile', authenticate, (req, res) => { ... });
app.get('/api/admin', authenticate, authorize('admin'), (req, res) => { ... });
```

## Tích hợp
Xem `AGENT.md` trong cùng thư mục.

---
*Teams: chờ repo nguồn để tích hợp phần chủ chốt.*
