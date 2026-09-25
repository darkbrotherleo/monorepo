# AGENT.md — lib/auth

## Mục Đích
Module xác thực & phân quyền tái sử dụng: đăng ký, đăng nhập, JWT, OAuth, middleware bảo vệ route, quản lý role/permission.

## Kiến Trúc Dự Kiến
```
lib/auth/
├── AGENT.md          # File này
├── README.md         # Hướng dẫn sử dụng
├── src/
│   ├── controllers/  # AuthController
│   ├── middleware/   # authenticate, authorize
│   ├── services/     # JWT, OAuth, password hashing
│   ├── models/       # User, Token
│   └── routes/       # Route auth
└── config/
```

## Cách Tích Hợp Vào Dự Án
1. Copy thư mục `lib/auth/src/` vào dự án (hoặc link).
2. Cài dependencies: `jwt`, `bcrypt`, `passport` (tùy ngôn ngữ).
3. Import middleware vào router chính.
4. Config `JWT_SECRET`, `JWT_EXPIRES_IN` trong `.env`.

## Dependencies / Yêu Cầu
- Database có bảng `users` (migration `20260923000001_create_users.php` trong starter)
- Biến môi trường: `JWT_SECRET`, `JWT_EXPIRES_IN`, `OAUTH_CLIENT_ID` (nếu OAuth)
- Composer: `firebase/php-jwt ^6.0`, `tuupola/slim-jwt-auth ^3.0` (battle-tested), alternative `cube43/slim-jwt-auth ^1.0` (hydrator+firewall tách lớp)

## Lưu Ý
- Không hardcode secret — dùng `$_ENV['JWT_SECRET']` (dotenv).
- Luôn hash password trước khi lưu (`password_hash`).
- Token refresh nên lưu httpOnly cookie; `JwtFirewall` đã config `path: /api`, `ignore: /api/login, /health`, `secure` chỉ production (tuupola docs).
- Firewall LIFO: `JwtAuthentication` phải chạy trước firewall check — `JwtFirewall::create()` đã wrap đúng.

## Trạng Thái
🟡 Mẫu khởi tạo — chờ tích hợp repo thực tế theo quy trình mục 4 của `AGENT.md` gốc.

---
*Cập nhật: 2026-09-23*
