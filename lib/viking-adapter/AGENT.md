# AGENT.md — lib/viking-adapter

## Mục Đích
Thin Guzzle adapter tới **external** OpenViking server (`volcengine/OpenViking`, 38.5k★, AGPL-3.0). OpenViking là Context Database tự tiến hóa: Memory+Knowledge RAG+Skills dưới dạng filesystem ảo `viking://` (`resources/`, `user/{id}/memories|skills|peers`), inspect bằng `ls/tree/read/grep`. Giữ AGPL ngoài monorepo — chỉ adapter MIT trong `lib/`.

## Kiến Trúc / File Chính
```
lib/viking-adapter/
├── AGENT.md        # File này
├── README.md       # Hướng dẫn chạy server ngoài + dùng adapter
├── composer.json   # guzzlehttp/guzzle ^7.8, PHP >=8.2
├── .env.example    # OPENVIKING_URL/KEY/ACCOUNT/USER/ACTOR_PEER_ID
├── src/
│   └── VikingClient.php  # add_resource→poll, find/search/ls/read (mirror SDK Python/TS)
└── scripts/
    ├── up.ps1            # docker pull + compose up
    └── docker-compose.yml # ghcr.io/volcengine/openviking:latest, ports 1933/1934
```
Server gốc: Python 3.10 + Rust crates + C++ VikingVectorIndex + FastAPI/Uvicorn + Docker/Caddy — **không copy** vào monorepo (đúng lọc "chỉ phần chủ chốt": chỉ adapter).

## Cách Tích Hợp Vào Dự Án
1. Chạy server ngoài: `.\scripts\up.ps1` (cần Docker). Hoặc `docker pull ghcr.io/volcengine/openviking:latest` + `docker compose -f scripts/docker-compose.yml up -d`.
2. `composer require guzzlehttp/guzzle` (hoặc `composer install` trong `lib/viking-adapter/`).
3. Copy `src/VikingClient.php` + `.env.example` → dự án `C:\laragon\www\ten-du-an\`, điền `.env`.
4. Dùng:
   ```php
   $viking = new \Monorepo\Viking\VikingClient($_ENV['OPENVIKING_URL'], $_ENV['OPENVIKING_API_KEY']);
   $task = $viking->addResource('/path/to/project', 'wing-php');
   $viking->pollTask($task['task_id']);
   $hits = $viking->search('Slim middleware auth', 10);
   $ls = $viking->ls('viking://user/local-dev/memories');
   ```
5. Chọn via `shared/memory/router.md` — khi nào dùng Viking vs claude-mem/mempalace (Viking khi cần scaled RAG + virtual filesystem).

## Dependencies / Yêu Cầu
- PHP >=8.2, `guzzlehttp/guzzle` ^7.8 (trong `lib/php-stack` đã có Guzzle).
- Server: Docker + Linux (C++ engine), ports 1933/1934, tree-sitter-php 0.24.1 phía server.

## Lưu Ý / Bài Học
- AGPL: server phải chạy ngoài, không vendor code — adapter viết mới MIT thì an toàn.
- Đừng chạy 2 memory song song trên cùng dự án (bài học 2026-09-23). Viking chỉ khi cần RAG quy mô (LoCoMo 80-83%).
- viking:// `find` (directory-scoped) vs `search` (semantic) — chọn đúng scope để tiết kiệm token.

---
*Cập nhật: 2026-09-23 — Tạo viking-adapter (thin Guzzle, AGPL ngoài), lọc bỏ openviking/crates/C++ engine*
