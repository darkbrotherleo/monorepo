# AGENT.md — Bản Đồ Điều Hướng Monorepo

> ⚠️ **BẮT BUỘC ĐỌC TRƯỚC MỌI THAO TÁC** — Mọi Agent/Người dùng phải đọc file này trước khi thực hiện bất kỳ yêu cầu nào trong monorepo.

---

## 1. Mục Tiêu Của Monorepo

Đây là **Feature Library Monorepo** — kho chứa các tính năng (lib) có thể tái sử dụng nhiều lần, không phải nơi chứa dự án thực thi.

| Mục tiêu | Mô tả |
|----------|-------|
| **Tái sử dụng** | Mỗi tính năng trong `lib/` là một module độc lập, có thể copy/tích hợp vào bất kỳ dự án nào bên ngoài |
| **Lưu trữ tri thức** | Mọi kinh nghiệm, lỗi, cách sửa, bài học được ghi vào `EXPERIENCE.md` để Agent đời sau kế thừa |
| **Kỷ luật cấu trúc** | Tuyệt đối tuân thủ cấu trúc thư mục đã quy định. Không tự ý phá vỡ |
| **Không chứa dự án thật** | Mọi dự án triển khai PHẢI nằm ngoài monorepo (ví dụ `C:\laragon\www\ten-du-an`) |

---

## 2. Quy Tắc Hoạt Động

### 2.1 Nguyên tắc chung
1.  **Quy tắc Ponytail (The Ladder):** Luôn leo thang quyết định trước khi code: YAGNI > Reuse > Stdlib > Native > Installed > One-line > Minimum.
2.  **Luôn ưu tiên tái sử dụng** code có sẵn trong `lib/` và `shared/` trước khi viết mới.
3.  **Không phá vỡ cấu trúc** đã quy định trong `STRUCTURE.md`.
4.  **Mọi thay đổi quan trọng** phải ghi vào `EXPERIENCE.md` (theo format ngày tháng + phân loại).
5.  **Khi thông tin chưa đủ rõ** — phải hỏi lại người dùng, tuyệt đối không tự giả định.
6.  **Đọc `AGENT.md` gốc** trước, sau đó đọc `AGENT.md` riêng của tính năng liên quan trước khi thao tác.


### 2.2 Quy tắc với Git
- Không commit code lỗi / chưa được verify.
- Mỗi tính năng trong `lib/` nên có README mô tả cách sử dụng.

### 2.3 Quy tắc với EXPERIENCE.md
- Cập nhật ngay khi: gặp lỗi + đã xử lý xong, tìm ra giải pháp tốt, rút ra bài học/pattern, đưa ra quyết định quan trọng.
- Format: `[YYYY-MM-DD] [Phân loại] Tiêu đề — Nội dung`

---

## 3. Mapping Tính Năng → Đường Dẫn

| Tính năng | Đường dẫn | Mô tả | Trạng thái |
|-----------|-----------|-------|------------|
| Auth | `lib/auth/` | JWT (HS256 verify), password_hash, middleware PSR-15 | 🟢 Code thật |
| Payment | `lib/payment/` | Strategy Stripe/VNPay, PaymentService + Webhook verify raw body | 🟢 Code thật |
| Notification | `lib/notification/` | Symfony Mailer wrapper | 🟢 Code thật |
| Ponytail | `lib/ponytail/` | Bộ quy tắc tối giản code (The Ladder) | 🟢 Đã kích hoạt |
| Headroom | `lib/headroom/` | Context compression (proxy/library/MCP), tiết kiệm 20-95% token | 🟢 Đã tích hợp |
| Claude-mem | `lib/claude-mem/` | Bộ nhớ bền vững, persistent context qua sessions | 🟢 Đã tích hợp |
| Mempalace | `lib/mempalace/` | Local-first verbatim memory, 96.6% R@5 LongMemEval | 🟢 Đã tích hợp |
| UI-taste | `lib/ui-taste/` | Anti-slop frontend (13 taste SKILL.md + pro-max engine offline) | 🟢 Đã tích hợp |
| PHP-stack | `lib/php-stack/` | PHP thuần/Slim + starter chạy được (public/index.php + migrations + CI) | 🟢 Sẵn sàng deploy |
| Security-audit | `lib/security-audit/` | Audit vibe-coded (7 checks Slim: secrets/auth/SQLi/payments...) | 🟢 Đã tích hợp |
| Viking-adapter | `lib/viking-adapter/` | Thin Guzzle adapter tới OpenViking external (AGPL ngoài) | 🟢 Đã tích hợp |
| Web-automation | `lib/web-automation/` | Browser automation + Scrapling MCP (adaptive + spiders + RAG) | 🟢 Đã tích hợp |
| Cheatsheets | `shared/cheatsheets/` | Cheatsheets lọc (php/git/docker/nginx/mysql + slim supplement) | 🟢 Đã tích hợp |
| Memory (chung) | `shared/memory/` | Abstraction gộp claude-mem + mempalace (router quy ước) | 🟢 Quy ước markdown |
| Shared | `shared/` | Code dùng chung: helpers, utils, constants, types, middleware chung | 🟡 Mẫu khởi tạo |

> Khi thêm tính năng mới, cập nhật bảng này ngay. Xem thêm mục 4.

### Cấu trúc chi tiết mỗi tính năng
```
lib/<ten-tinh-nang>/
├── AGENT.md        # Bắt buộc — mô tả tính năng, cách dùng, dependencies
├── README.md       # Hướng dẫn sử dụng, API, ví dụ
└── ... (code)      # src/, config/, ...
```

---

## 4. Hướng Dẫn Tích Hợp Repo Tính Năng Mới

Khi người dùng đề xuất một repo tính năng (ví dụ: link GitHub repo auth):

1.  **Phân tích thông minh**: Đọc `README`, cấu trúc thư mục, code chính (entry point, config, core logic).
2.  **Lọc giá trị cốt lõi**: Chỉ tích hợp những phần chủ chốt, có giá trị tái sử dụng cao. Loại bỏ code demo/rác, config dự án cụ thể.
3.  **Tạo thư mục mới** trong `lib/` với tên theo tính năng (kebab-case): `lib/<ten-tinh-nang>`
4.  **Tạo `AGENT.md` riêng** bên trong thư mục tính năng, bao gồm:
    - Mục đích tính năng
    - Kiến trúc / file chính
    - Cách tích hợp vào dự án
    - Dependencies / yêu cầu môi trường
    - Lưu ý / bài học
5.  **Tạo `README.md`** mô tả cách sử dụng.
6.  **Cập nhật `AGENT.md` gốc** (file này) — thêm dòng mới vào bảng Mapping ở mục 3.
7.  **Ghi log vào `EXPERIENCE.md`** — quyết định tích hợp, lý do chọn lọc, bài học.

```
Quy trình: Phân tích → Lọc → Tạo thư mục lib/<feature> → Thêm AGENT.md + README.md → Cập nhật AGENT.md gốc → Ghi EXPERIENCE.md
```

---

## 5. Hướng Dẫn Triển Khai Dự Án (RẤT QUAN TRỌNG)

### ⛔ Tuyệt đối không tạo thư mục dự án bên trong monorepo

### Quy trình bắt buộc:

**Bước 1 — Hỏi và xác nhận đủ 5 thông tin sau:**
1.  Ngôn ngữ lập trình sử dụng (PHP/Laravel, Node.js, Python, ...)
2.  Môi trường server (Apache, Nginx, Laragon, Docker, ...)
3.  Môi trường Database (MySQL, PostgreSQL, SQLite, MongoDB, ...)
4.  Đường dẫn tuyệt đối muốn tạo dự án (ví dụ: `C:\laragon\www\ten-du-an`)
5.  Các yêu cầu đặc biệt khác (tính năng cần dùng từ lib, giao diện, ...)

**Bước 2 — Chỉ khi người dùng xác nhận đầy đủ mới được:**
- Tạo dự án ở **đường dẫn bên ngoài** đã xác nhận
- Copy / sử dụng code từ `lib/` và `shared/` vào dự án
- Cài đặt dependencies, config môi trường

**Bước 3 — Ghi log triển khai vào `EXPERIENCE.md`**

> Nếu thiếu bất kỳ thông tin nào trong 5 mục trên → DỪNG LẠI và hỏi tiếp, không tự giả định.

---

## 6. Tham Chiếu Nhanh

| File | Vai trò |
|------|---------|
| `AGENT.md` (gốc) | Bản đồ điều hướng — đọc đầu tiên |
| `EXPERIENCE.md` | Nhật ký kinh nghiệm Agent |
| `STRUCTURE.md` | Mô tả kiến trúc tổng quan |
| `lib/<feature>/AGENT.md` | Tài liệu riêng từng tính năng |
| `shared/` | Code dùng chung toàn monorepo |

---
*Cập nhật: 2026-09-23 — Gộp Scrapling MCP (adaptive + spiders + RAG) vào web-automation (bạn dùng nhiều) + trước đó JWT/VNPay/rate-limit*
