# AGENT.md — lib/php-stack

## Mục Đích
Bản đồ tri thức PHP cho chuyên môn PHP thuần/Slim: chọn nhanh thư viện đáng dùng thay vì tự sưu tầm. Nguồn: `ziadoz/awesome-php` (32.7k★, curated list). Lấp mảng PHP còn thiếu (trước đó chỉ có mẫu phi ngôn ngữ + UI/memory/compression). Không phải vendor, không chứa code chạy — chỉ bản đồ + composer mẫu.

## Kiến Trúc / File Chính
```
lib/php-stack/
├── AGENT.md              # File này
├── README.md             # Bảng rút gọn ~30 lib lõi theo Slim + link gốc awesome-php
├── composer.json         # Full: Slim 4 + Plates + PHP-DI + Monolog + Dotenv + PHPUnit/Pest
├── composer-slim.json    # Minimum: Slim + PHP-DI only (Ponytail Minimum)
└── .env.example          # APP_ENV, DB_*, JWT_SECRET mẫu
```
Đã lọc bỏ: Frameworks nặng (Laravel/Symfony), CMS, E-commerce platform, Software/VM, Videos/Books/Podcasts (chỉ giữ link tổng), Extensions/LLMs dư thừa — vì đã chốt PHP thuần/Slim + tham khảo curated-list.

## Cách Tích Hợp Vào Dự Án
**Khi triển khai dự án ngoài (mục 5 AGENT gốc) với Slim/PHP thuần:**
1. Copy `composer.json` (full) hoặc `composer-slim.json` (minimum) ra dự án ngoài `C:\laragon\www\ten-du-an\composer.json`.
2. `composer install` (PHP >=8.2, Composer 2).
3. Copy `.env.example` → `.env` và điền DB/JWT.
4. Cấu trúc gợi ý từ awesome-php: `public/index.php` (Slim app) + `templates/` (Plates) + `src/` (container PHP-DI).
5. Chọn thêm lib theo `README.md` khi cần: `composer require <package>` (vd. `respect/validation`, `firebase/php-jwt`).

Không copy cả `lib/php-stack/` vào dự án — chỉ copy 2 file composer + .env.

## Dependencies / Yêu Cầu
- PHP >=8.2, Composer 2, ext: `ext-mbstring`, `ext-pdo`, `ext-json`, `ext-openssl` (cho JWT).
- Runtime: Slim 4 + `slim/psr7`, DI: `php-di/php-di`, Template: `league/plates`, Log: `monolog/monolog`, Env: `vlucas/phpdotenv`.
- Dev: `phpunit/phpunit` hoặc `pestphp/pest`.

## Lưu Ý / Bài Học
- Ưu tiên PSR (PSR-7/11/15) khi chọn lib — dễ thay thế, tránh lock-in.
- Kiểm tra lib còn maintain (commit <6 tháng, có release cho PHP 8.2+) trước khi `composer require`.
- Ponytail: bắt đầu với `composer-slim.json` (YAGNI), chỉ thêm lib khi task yêu cầu rõ (Respect Validation, Guzzle...). Link gốc awesome-php để tra cứu định kỳ — curated list có thể stale.

---
*Cập nhật: 2026-09-23 — Tích hợp ziadoz/awesome-php theo hướng PHP thuần/Slim (bản đồ + composer mẫu, lọc bỏ Framework nặng/CMS)*
