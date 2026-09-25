# Feature Library Monorepo

Kho chứa các tính năng (lib) có thể tái sử dụng nhiều lần + nơi lưu trữ tri thức Agent.

> ⚠️ **Nguyên tắc vàng:** Monorepo này **không chứa dự án thực thi**. Mọi dự án thật phải tạo ở bên ngoài (ví dụ `C:\laragon\www\ten-du-an`).

## Cấu Trúc

```
monorepo-root/
├── AGENT.md          # Bản đồ điều hướng — đọc trước tiên
├── EXPERIENCE.md     # Nhật ký kinh nghiệm
├── STRUCTURE.md      # Mô tả kiến trúc
├── lib/              # Các tính năng tái sử dụng
│   ├── auth/
│   ├── payment/
│   └── notification/
└── shared/           # Code dùng chung
```

## Bắt Đầu Nhanh

1. Đọc `AGENT.md` để nắm quy tắc.
2. Xem `STRUCTURE.md` để hiểu kiến trúc.
3. Duyệt `lib/<feature>/README.md` để dùng tính năng.
4. Khi tích hợp repo mới → làm theo mục 4 trong `AGENT.md`.
5. Khi triển khai dự án → làm theo mục 5 trong `AGENT.md` (phải xác nhận đủ 5 thông tin).

## Thêm Tính Năng Mới

```bash
# Tạo thư mục tính năng
mkdir lib/ten-tinh-nang
# Thêm AGENT.md + README.md bắt buộc
# Cập nhật mapping trong AGENT.md gốc
# Ghi log vào EXPERIENCE.md
```

## Triển Khai Dự Án

Tuyệt đối tạo dự án **bên ngoài** monorepo. Liên hệ Agent và cung cấp đủ:
- Ngôn ngữ, server, database, đường dẫn tuyệt đối, yêu cầu đặc biệt.

## Đóng Góp

- Mọi thay đổi quan trọng → ghi vào `EXPERIENCE.md`.
- Không tự ý phá vỡ cấu trúc.

---
*Khởi tạo: 2026-09-23*
