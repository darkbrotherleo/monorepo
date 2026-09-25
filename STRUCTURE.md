# STRUCTURE.md — Kiến Trúc Monorepo

## 1. Tổng Quan

```
monorepo-root/
├── AGENT.md                 # Bản đồ điều hướng chính (bắt buộc đọc trước mọi thao tác)
├── EXPERIENCE.md            # Nhật ký kinh nghiệm, lỗi, bài học, quyết định
├── STRUCTURE.md             # File này — mô tả kiến trúc tổng quan
├── README.md                # Giới thiệu monorepo
├── .gitignore               # Loại trừ file không cần thiết
├── Prompt.md                # Prompt gốc định nghĩa quy tắc
├── lib/                     # Kho tính năng tái sử dụng
│   ├── auth/                # Tính năng xác thực
│   │   ├── AGENT.md
│   │   └── README.md
│   ├── payment/             # Tính năng thanh toán
│   │   ├── AGENT.md
│   │   └── README.md
│   ├── notification/        # Tính năng thông báo
│   │   ├── AGENT.md
│   │   └── README.md
│   ├── ponytail/            # Tư duy tối giản (The Ladder)
│   │   ├── AGENT.md
│   │   ├── README.md
│   │   └── rules/           # Cấu hình IDE (.cursorrules, ...)
│   ├── headroom/            # Context compression (proxy/MCP/learn)
│   │   ├── AGENT.md
│   │   ├── README.md
│   │   └── scripts/         # install.ps1, wrap.ps1
│   ├── claude-mem/          # Persistent memory (sessions → SQLite/Chroma)
│   │   ├── AGENT.md
│   │   ├── README.md
│   │   └── scripts/         # install.ps1, configure.ps1
│   ├── mempalace/           # Verbatim memory (wings→rooms→drawers, 45 MCP tools)
│   │   ├── AGENT.md
│   │   ├── README.md
│   │   └── scripts/         # install.ps1 (uv tool install)
│   ├── ui-taste/            # Anti-slop frontend (14 SKILL.md + engine offline)
│   │   ├── AGENT.md
│   │   ├── README.md
│   │   ├── skills/          # design-taste-*, imagegen-*, brandkit, ui-ux-pro-max (router)
│   │   └── engine/          # search.py + data/*.csv (79 styles/192 palettes) + usage.md
│   ├── php-stack/           # PHP thuần/Slim (bản đồ + composer mẫu + starter chạy được)
│   │   ├── AGENT.md
│   │   ├── README.md
│   │   ├── composer.json         # Full: Slim + Plates + PHP-DI + Monolog + Dotenv
│   │   ├── composer-slim.json    # Minimum: Slim + PHP-DI
│   │   ├── .env.example
│   │   └── starter/             # public/index.php + config/* + migrations + tests + .htaccess + openapi.yaml
│   ├── security-audit/      # Audit vibe-coded (SKILL.md + 7 refs Slim)
│   │   ├── AGENT.md
│   │   ├── SKILL.md
│   │   ├── README.md
│   │   └── references/      # secrets/auth/rate-limit/payments/data-access/deployment/ai
│   ├── viking-adapter/      # Thin Guzzle → external OpenViking (AGPL ngoài)
│   │   ├── AGENT.md
│   │   ├── src/VikingClient.php
│   │   └── scripts/docker-compose.yml
│   ├── web-automation/      # Browser automation + Scrapling MCP (adaptive + spiders)
│   │   ├── AGENT.md
│   │   ├── src/             # CommandCatalog/StallDetector/UsageMeter/HtmlToMarkdown/ScraplingClient
│   │   ├── scrapling/       # requirements.txt + mcp.json + skill/SKILL.md + examples
│   │   ├── service/docker-compose.yml            # openbrowser
│   │   └── service/docker-compose.scrapling.yml  # Scrapling sidecar
└── shared/                  # Code dùng chung toàn monorepo
     ├── memory/              # Abstraction gộp claude-mem + mempalace (+ viking khi scale)
│   │   ├── README.md
│   │   └── router.md        # Quy tắc chọn backend (chỉ markdown, chưa code)
     ├── cheatsheets/         # Cheatsheets lọc (php/git/docker/nginx/mysql + slim-supplement)
     │   ├── README.md
     │   └── slim-supplement.md
     └── README.md
```

## 2. Mô Tả Chi Tiết

### 2.1 Tầng Điều Hướng (`AGENT.md`, `STRUCTURE.md`, `README.md`)
- `AGENT.md` gốc: Single source of truth cho Agent — chứa mục tiêu, quy tắc, mapping tính năng, hướng dẫn tích hợp & triển khai.
- `STRUCTURE.md` (file này): Mô tả kiến trúc thư mục, trách nhiệm từng phần.
- `README.md`: Giới thiệu cho con người, hướng dẫn nhanh.

### 2.2 Tầng Tri Thức (`EXPERIENCE.md`)
- Lưu trữ kinh nghiệm xuyên suốt vòng đời monorepo.
- Không gắn với dự án cụ thể → tái sử dụng cho mọi dự án sau này.
- Format log có ngày tháng + phân loại `[Lỗi] / [Cách sửa] / [Bài học] / [Quyết định]`.

### 2.3 Tầng Tính Năng (`lib/`)
- Mỗi thư mục con là một **feature module độc lập**, có thể copy nguyên vẹn vào dự án ngoài.
- Quy ước đặt tên: `kebab-case` (ví dụ: `auth`, `payment`, `user-management`).
- Bên trong mỗi feature:
  - `AGENT.md` (bắt buộc): Mô tả mục đích, kiến trúc, cách tích hợp, dependencies.
  - `README.md` (bắt buộc): Hướng dẫn sử dụng, API, ví dụ code.
  - `src/` / `config/` / ... (tùy tính năng).
- Nguyên tắc: **Chỉ tích hợp phần chủ chốt, có giá trị tái sử dụng cao** khi import từ repo ngoài.

### 2.4 Tầng Dùng Chung (`shared/`)
- Chứa code share giữa nhiều tính năng: helpers, utils, constants, types, middleware chung.
- Không chứa logic nghiệp vụ đặc thù của một tính năng.
- Các feature trong `lib/` có thể import từ `shared/` nhưng không phụ thuộc chéo lẫn nhau (tránh circular).

## 3. Quy Tắc Phụ Thuộc

```
shared/  ←  lib/auth, lib/payment, lib/notification
shared/memory/  ←  lib/claude-mem, lib/mempalace  (router chọn backend)
lib/headroom  →  proxy cho mọi Agent (không phụ thuộc lib khác)
lib/*  ↮  lib/*   (không phụ thuộc chéo trực tiếp, trừ shared/memory)
Dự án ngoài  ←  lib/*  +  shared/   (copy/import)
```

- `lib/*` → được phép phụ thuộc `shared/`
- `lib/*` → không phụ thuộc lẫn nhau (nếu cần, tách phần chung ra `shared/`)
- Dự án ngoài → copy hoặc link code từ `lib/` + `shared/`

## 4. Quy Tắc Mở Rộng

| Hành động | Vị trí | Cần cập nhật |
|-----------|--------|--------------|
| Thêm tính năng mới | `lib/<ten-moi>/` | `AGENT.md` gốc (mapping) + `EXPERIENCE.md` |
| Thêm code dùng chung | `shared/` | `README.md` của `shared/` |
| Sửa kiến trúc | `STRUCTURE.md` | `AGENT.md` gốc + `EXPERIENCE.md` |

## 5. Những Gì KHÔNG Thuộc Monorepo

- ❌ Thư mục dự án thực thi (`my-project/`, `app/`, `src/` ở root)
- ❌ File `.env` chứa secret
- ❌ `node_modules/`, `vendor/`, `build/`, `dist/`
- ❌ Database file, upload files

> Mọi dự án thật phải được tạo ở **bên ngoài** monorepo, ví dụ `C:\laragon\www\ten-du-an`.

---
*Cập nhật: 2026-09-23 — Gộp Scrapling MCP vào web-automation + trước đó JWT/VNPay/rate-limit*
