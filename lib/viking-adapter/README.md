# lib/viking-adapter — Thin adapter tới OpenViking (external)

> OpenViking là Context Database tự tiến hóa (Memory+RAG+Skills). Server AGPL chạy ngoài, adapter MIT trong monorepo.

## Chạy server ngoài
```powershell
.\scripts\up.ps1
# API http://127.0.0.1:1933  Studio http://127.0.0.1:1934
```

## Dùng adapter (PHP Slim)
```php
$viking = new \Monorepo\Viking\VikingClient($_ENV['OPENVIKING_URL'], $_ENV['OPENVIKING_API_KEY']);
$task = $viking->addResource('/path/to/project');
$viking->pollTask($task['task_id']);
$hits = $viking->search('auth middleware', 10);
$ls = $viking->ls('viking://user/local-dev/memories');
$text = $viking->read('viking://resources/myapp/README.md');
```

## Khi nào dùng
Xem `shared/memory/router.md` — Viking khi cần virtual filesystem + scaled RAG (LoCoMo 80-83%), claude-mem cho timeline nhanh, mempalace cho verbatim.

## Cài đặt
```bash
composer require guzzlehttp/guzzle
# hoặc
composer install # trong lib/viking-adapter/
```

---
*Source: volcengine/OpenViking 38.5k★ — chỉ adapter, không vendor server.*
