# lib/web-automation — Browser automation + Scraping cho AI agent

> Nguồn: `ntegrals/openbrowser` (Bun/TS agent loop) + `D4Vinci/Scrapling` (Python adaptive + MCP) — không vendor runtime.

## Khi nào dùng

| Task | Chọn |
|------|------|
| Scraper đơn, adaptive selectors, Cloudflare bypass, spiders, RAG markdown | **Scrapling MCP** (khuyên dùng — bạn sẽ dùng nhiều) |
| Agent loop click/type/scroll/extract với LLM | openbrowser sidecar |
| PHP-only, không sidecar | Panther + `src/*` port |

CRUD/API thuần thì bỏ qua.

## Scrapling MCP (trọng tâm)
```bash
pip install "scrapling[fetchers,ai]" && scrapling install
# scrapling/scrapling/mcp.json → thêm vào Claude Code / Cursor / OpenCode
scrapling extract get 'https://example.com' content.md --css-selector '.product'
```

```python
from scrapling.fetchers import Fetcher
page = Fetcher.get('https://example.com')
page.css('.product', auto_save=True)
page.css('.product', adaptive=True)  # tự relocate khi đổi layout
page.markdown()  # RAG-ready
```

```php
// Slim
use Monorepo\WebAutomation\ScraplingClient;
$md = (new ScraplingClient())->markdown('https://example.com');
```

## Openbrowser sidecar
```powershell
docker compose -f service/docker-compose.yml up -d
npx open-browser run "extract pricing from https://example.com" -p openai --json
.\scripts\call.ps1 -Task "my task" -Provider openai
```

## XPath trang tĩnh
```php
// PHP (cao-xpath.php): DOMXPath tương đương Scrapling page.xpath()
$doc = new DOMDocument(); @$doc->loadHTML($html); $xp = new DOMXPath($doc);
$names = $xp->query('//div[@class="quote"]//span[@class="text"]/text()');
```
```python
# Scrapling
page = Fetcher.get('https://quotes.toscrape.com/')
page.xpath('//div[@class="quote"]//span[@class="text"]/text()').getall()
page.xpath('//div[@class="quote"]', auto_save=True) # adaptive sau này
```
Demo: `http://demofull.test/cao-xpath.php` và `lib/web-automation/scrapling/examples/06_xpath_static.py`

## PHP port (nhẹ)
```php
use Monorepo\WebAutomation\HtmlToMarkdown;
$md = HtmlToMarkdown::convert($html);
```

## Cài
```bash
composer require symfony/panther symfony/dom-crawler guzzlehttp/guzzle
```

---
*openbrowser MIT + Scrapling BSD-3-Clause — chỉ pattern/instruction, không copy runtime.*
