# lib/php-stack — Bản đồ thư viện PHP thuần / Slim

> Tham khảo rút gọn từ `ziadoz/awesome-php` (32.7k★) — đã lọc theo chuyên môn PHP thuần/Slim của bạn.

## Dùng nhanh
```bash
# Full (Slim + Plates + PHP-DI + Monolog + Dotenv)
copy lib\php-stack\composer.json C:\laragon\www\ten-du-an\composer.json
copy lib\php-stack\.env.example C:\laragon\www\ten-du-an\.env
composer install

# Minimum (Slim + PHP-DI only — Ponytail Minimum)
copy lib\php-stack\composer-slim.json C:\laragon\www\ten-du-an\composer.json
composer install
```

## Bảng lõi (đã lọc — chỉ thứ cần cho Slim)

| Category (awesome-php) | Lib khuyên dùng | Composer | Khi nào dùng |
|---|---|---|---|
| Micro Frameworks | **Slim 4** | `slim/slim` + `slim/psr7` | Mặc định mọi dự án PHP thuần |
| Routers | FastRoute (đã trong Slim) | — | Đủ, không cần Klein/Route riêng |
| Templating | **Plates** (native PHP) | `league/plates` | Nhanh, không compile; Twig nếu cần sandbox |
| Dependency Injection | **PHP-DI** | `php-di/php-di` | Autowiring, PSR-11 |
| HTTP | **Guzzle** | `guzzlehttp/guzzle` | Gọi API ngoài; HTTPlug nếu cần abstraction |
| Logging | **Monolog** | `monolog/monolog` | PSR-3, đủ mọi dự án |
| Configuration | **PHP Dotenv** | `vlucas/phpdotenv` | Load .env |
| Validation | Respect/Valitron | `respect/validation` | Validate input |
| Auth/JWT | firebase/php-jwt | `firebase/php-jwt` | JWT cho `lib/auth` |
| Email | Symfony Mailer / PHPMailer | `symfony/mailer` | Gửi mail (`lib/notification`) |
| Files | Flysystem | `league/flysystem` | Storage abstraction |
| Date/Time | Carbon/Chronos | `nesbot/carbon` | DateTime API |
| Testing | **PHPUnit** + Pest | `phpunit/phpunit`, `pestphp/pest` | Unit test |
| Security | Halite / roave/security-advisories | `paragonie/halite` | Encryption + check CVE |
| Code Quality | PHP-CS-Fixer, PHPStan, Psalm | `friendsofphp/php-cs-fixer` | Lint + static analysis |
| URL | PHP Domain Parser | `jeremykendall/php-domain-parser` | Parse domain |
| Strings | Slugify, Stringy | `cocur/slugify` | Slug, multibyte |

Link gốc đầy đủ: `https://github.com/ziadoz/awesome-php`

## Phân biệt với các lib/ khác
- `lib/auth` → dùng `firebase/php-jwt` + `respect/validation` từ bảng trên
- `lib/payment` → `omnipay/omnipay` hoặc gateway riêng (VNPay/Momo) — không có trong awesome-php core, tự implement
- `lib/notification` → `symfony/mailer` từ bảng trên

## Cấu trúc gợi ý cho dự án Slim
```
ten-du-an/
├── public/index.php   # Slim App + PHP-DI container
├── templates/         # Plates
├── src/               # Controllers/Services
├── composer.json
└── .env
```

## Lưu ý
Bắt đầu với `composer-slim.json` (YAGNI), thêm lib khi task yêu cầu. Kiểm tra maintain status trước khi require.
