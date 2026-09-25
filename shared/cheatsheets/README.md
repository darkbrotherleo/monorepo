# shared/cheatsheets — Cheatsheets tham khảo (lọc từ awesome-cheatsheets)

> Nguồn: `LeCoupa/awesome-cheatsheets` (46.5k★, MIT). Chỉ copy phần lõi đã lọc, đúng Ponytail Minimum.

## Files

| File | Nguồn gốc | Dùng khi nào |
|---|---|---|
| `php.php` | `languages/php.php` (~15k) | PHP 8.0+ fundamentals (match, enums, union types) — bổ sung cho `lib/php-stack` |
| `git.sh` | `tools/git.sh` (9.6k) | 250 cmds git thường dùng |
| `docker.sh` | `tools/docker.sh` (6.3k) | Docker system/prune/compose — **lưu ý** còn `docker-machine`/compose v1 cũ, đã prune không cần |
| `nginx.sh` | `tools/nginx.sh` (5.1k) | Nginx headers, vhost — dùng cho Laragon Nginx deploy |
| `mysql.sh` | `databases/mysql.sh` (6.5k) | MySQL DDL/index/transaction/processlist |
| `bash.sh` | `languages/bash.sh` (27k) | Bash scripting cho `shared/` tasks |
| `html5.html` | `frontend/html5.html` (14k) | HTML5 cho Plates templates |
| `curl.sh` | `tools/curl.sh` (6.3k) | curl API test |
| `slim-supplement.md` | **Tự viết** | Bù Slim 4 + PHP-DI + Plates + middleware (upstream không có) |

## Đã lọc bỏ
`backend/laravel.php` (5.8 cũ), Node/Django/Adonis, React/Vue/Angular, mongo/redis, drush/nanobox/heroku/docker-machine, C/C#/Go/Java, macOS/xcode/sublime.

## Cập nhật lại
```powershell
iwr https://raw.githubusercontent.com/LeCoupa/awesome-cheatsheets/master/languages/php.php -OutFile shared/cheatsheets/php.php
```

## Liên quan
- `lib/php-stack/README.md` (bảng 15 lib Slim)
- `shared/cheatsheets/slim-supplement.md` (PSR-7/11/15, Plates)
