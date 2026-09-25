# AGENT.md — lib/web-automation

## Mục Đích
Cho AI agent điều khiển browser/scraping: (1) **openbrowser** (Bun+TS, 9.5k★) cho agent loop click/type/scroll/extract qua Playwright, (2) **Scrapling** (Python, 83.1k★, BSD-3-Clause) cho fetcher adaptive + spiders + MCP cho AI agent (trọng tâm bạn dùng nhiều). Không vendor runtime vào `lib/` — chỉ pattern + thin client + sidecar.

## Kiến Trúc / File Chính
```
lib/web-automation/
├── AGENT.md        # File này
├── README.md       # Khi nào dùng openbrowser vs Scrapling vs Panther native
├── composer.json   # guzzle, symfony/panther, dom-crawler (thay Playwright)
├── src/
│   ├── CommandCatalog.php # 25+ commands port (click/type/navigate/scroll/extract...)
│   ├── StallDetector.php  # hashPageTree/textContent → detect stall
│   ├── UsageMeter.php     # steps + cost + BudgetPolicy
│   ├── HtmlToMarkdown.php # extractMarkdown port (turndown → strip_tags)
│   └── ScraplingClient.php# thin bridge tới Scrapling sidecar (fetch/markdown)
├── scrapling/      # Scrapling MCP-first (bạn dùng nhiều)
│   ├── requirements.txt   # scrapling[fetchers,ai]
│   ├── mcp.json           # scrapling mcp serve (stdio, strip prompt-injection)
│   ├── skill/             # SKILL.md (24k) + 4 examples từ agent-skill/Scrapling-Skill
│   └── examples/          # 01_fetch_adaptive.py, 02_spider_pause_resume.py, 03_mcp_markdown_rag.py
├── service/
│   ├── docker-compose.yml            # openbrowser sidecar (ghcr.io/ntegrals/openbrowser)
│   └── docker-compose.scrapling.yml  # Scrapling sidecar (ghcr.io/d4vinci/scrapling)
└── scripts/
    └── call.ps1           # npx open-browser run "task" -p openai --json
```
Đã lọc bỏ: `bun` workspaces, `Viewport/EventHub`, `VercelModelAdapter`, Scrapling `scrapling/` source — chỉ giữ pattern/instruction (Ponytail Minimum).

## Cách Tích Hợp Vào Dự Án
**Mặc định: không tích hợp** — chỉ khi cần scraper/E2E/price-monitor/RAG.
1. **Scrapling MCP (khuyến nghị cho AI agent — trọng tâm):**
   ```bash
   pip install "scrapling[fetchers,ai]" && scrapling install
   # mcp.json đã có: scrapling mcp serve — thêm vào Claude Code/Cursor/OpenCode
   scrapling shell  # interactive
   scrapling extract get 'https://example.com' content.md
   ```
   ```python
   from scrapling.fetchers import Fetcher
   page = Fetcher.get('https://example.com')
   page.css('.product', auto_save=True)
   page.css('.product', adaptive=True)  # tự relocate khi site đổi
   page.markdown()  # RAG-ready, không cần LLM
   ```
   Slim: `new ScraplingClient()->markdown($url)` hoặc CLI `scrapling extract`.

2. **Openbrowser sidecar:**
   ```bash
   docker compose -f service/docker-compose.yml up -d
   .\scripts\call.ps1 -Task "extract pricing from https://example.com" -Provider openai
   ```

3. **Port native PHP-only:**
   ```php
   use Monorepo\WebAutomation\{CommandCatalog, StallDetector, UsageMeter, HtmlToMarkdown, ScraplingClient};
   $md = (new ScraplingClient())->markdown('https://example.com');
   ```

## Dependencies / Yêu Cầu
- Scrapling: Python 3.10+, `pip install "scrapling[fetchers,ai]"` + `scrapling install` (browsers), hoặc Docker `ghcr.io/d4vinci/scrapling`.
- Openbrowser sidecar: Bun + Playwright + LLM keys.
- Port native: PHP >=8.3, `symfony/panther`, `symfony/dom-crawler`, `guzzlehttp/guzzle`.

## Lưu Ý / Bài Học
- Chọn 1 sidecar mỗi task, không chạy openbrowser + Scrapling cùng lúc (tốn RAM/code).
- Scrapling MCP strip prompt-injection → agent đọc ít hơn, rẻ hơn, an toàn hơn vs openbrowser raw — phù hợp `lib/headroom` tiết kiệm token.
- Adaptive `auto_save` lưu selector lần đầu, `adaptive=True` tự tìm lại khi layout đổi — đúng nhu cầu dùng nhiều của bạn.
- StallDetector + BudgetPolicy phải giữ khi port.
- XPath (`page.xpath()` ↔ PHP `DOMXPath`) cho trang tĩnh chạy trực tiếp, không cần browser — demo `quotes.toscrape.com` 10 items live, tool `cao-xpath.php` tại demofull.

---
*Cập nhật: 2026-09-23 — Gộp Scrapling MCP-first (83.1k★, fetcher adaptive + spiders + skill) vào web-automation*
