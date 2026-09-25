# EXPERIENCE.md — Nhật Ký Kinh Nghiệm Monorepo

> File này lưu trữ tri thức tổng hợp của Agent, không phụ thuộc dự án cụ thể.
> Bắt buộc cập nhật mỗi khi: gặp lỗi + đã xử lý, tìm ra giải pháp tốt, rút ra bài học/pattern, đưa ra quyết định quan trọng.

## Quy Ước Format

```
### [YYYY-MM-DD] [Phân loại] Tiêu đề
- **Bối cảnh:** ...
- **Chi tiết:** ...
- **Kết quả / Bài học:** ...
```

**Phân loại:**
| Tag | Ý nghĩa |
|-----|---------|
| `[Lỗi]` | Lỗi đã gặp và cách tái hiện |
| `[Cách sửa]` | Cách khắc phục lỗi |
| `[Bài học]` | Lesson learned, pattern tái sử dụng |
| `[Quyết định]` | Quyết định kiến trúc / lựa chọn quan trọng |

---

## Lịch Sử Ghi Chép

### [2026-09-23] [Quyết định] Khởi tạo Feature Library Monorepo
- **Bối cảnh:** Tạo monorepo từ Prompt.md — yêu cầu xây dựng kho tính năng tái sử dụng, không chứa dự án thực thi.
- **Quyết định:**
  - Cấu trúc `monorepo-root/lib/<feature>/` cho từng tính năng độc lập.
  - `shared/` cho code dùng chung (helpers, utils, constants).
  - Mỗi tính năng bắt buộc có `AGENT.md` riêng + `README.md`.
  - `AGENT.md` gốc là bản đồ điều hướng, bắt buộc đọc trước mọi thao tác.
  - Ba tính năng mẫu khởi tạo: `auth`, `payment`, `notification`.
- **Lý do:** Đảm bảo tính kỷ luật, dễ điều hướng, dễ tích hợp repo mới và triển khai dự án bên ngoài.

### [2026-09-23] [Bài học] Quy tắc triển khai dự án bên ngoài monorepo
- **Bối cảnh:** Prompt yêu cầu tuyệt đối không tạo dự án trong monorepo.
- **Bài học:** Luôn xác nhận đủ 5 thông tin trước khi triển khai: ngôn ngữ, server, database, đường dẫn tuyệt đối, yêu cầu đặc biệt. Thiếu 1 mục cũng phải hỏi lại, không tự giả định.
- **Pattern tái sử dụng:** Checklist 5 mục → tạo dự án ngoài → copy từ `lib/`/`shared/` → cài dependencies → ghi log.

### [2026-09-23] [Quyết định] Tích hợp bộ quy tắc Ponytail (The Ladder)
- **Bối cảnh:** Cần tối ưu hóa code và tư duy Agent để tránh over-engineering (làm quá phức tạp hóa vấn đề).
- **Quyết định:**
  - Tạo `lib/ponytail` lưu trữ "The Ladder" (7 bậc quyết định).
  - Cập nhật `AGENT.md` gốc: đưa Ponytail lên ưu tiên số 1 trong quy tắc hoạt động.
  - Áp dụng triết lý: YAGNI > Reuse > Stdlib > Native > Installed > One-line > Minimum.
- **Lý do:** Giảm thiểu nợ kỹ thuật ngay từ bước thiết kế, tăng tốc độ thực thi và khả năng bảo trì.
- **Bài học:** Code tốt nhất là code không phải viết. Agent cần trace luồng thực tế trước khi chọn bậc thang.

### [2026-09-23] [Quyết định] Tích hợp Headroom (context compression)
- **Bối cảnh:** Cần giảm token cho coding agent (20% coding, 60-95% JSON) mà vẫn giữ câu trả lời nguyên vẹn. Repo `headroomlabs-ai/headroom` cung cấp library/proxy/MCP/reversible CCR.
- **Quyết định:**
  - Tạo `lib/headroom/` chỉ chứa instruction + scripts (`install.ps1` pip install, `wrap.ps1` proxy+wrap opencode), không copy code nguồn Rust/Python (cài qua pip — đúng Ponytail Reuse).
  - AGENT quy ước: kiểm tra `headroom proxy --port 8787` đầu session, sau task lớn chạy `headroom learn --apply` để tự cập nhật EXPERIENCE.md (đã được user cho phép).
- **Kết quả:** `lib/headroom/scripts/` trước đó rỗng → đã bổ sung 2 scripts tối giản; AGENT viết lại theo chuẩn 5 mục.

### [2026-09-23] [Quyết định] Tích hợp Claude-mem (persistent memory)
- **Bối cảnh:** Cần context sống qua sessions (X capture → AI summarize → inject). Repo `thedotmack/claude-mem` phù hợp OpenCode/Claude Code.
- **Quyết định:** Tạo `lib/claude-mem/` với `AGENT.md` (3 MCP tools: search/timeline/get_observations) + `scripts/install.ps1` + `configure.ps1`. Không nhúng toàn bộ codebase, chỉ lớp tương tác.
- **Lưu ý:** Trước đó AGENT gốc/STRUCTURE/EXPERIENCE chưa đồng bộ mapping — đã bổ sung ở đợt này.

### [2026-09-23] [Quyết định] Tích hợp MemPalace + gộp memory chung (shared/memory)
- **Bối cảnh:** Đã có `lib/claude-mem` (nhẹ, 3 MCP tools, progressive disclosure). Repo `MemPalace/mempalace` vượt trội về verbatim (96.6% R@5 raw, không tóm tắt, wings→rooms→drawers, 45 MCP tools, knowledge-graph temporal). User chọn hướng "gộp thành memory chung" và yêu cầu hoàn thiện cũ trước.
- **Quyết định:**
  - Tạo `lib/mempalace/` chỉ instruction + `scripts/install.ps1` (`uv tool install mempalace` → `mempalace init`), lọc bỏ `crates/`, `benchmarks/`, `landing/`, `website/`, GPU — đúng quy tắc "chỉ tích hợp phần chủ chốt".
  - Tạo `shared/memory/` chỉ markdown (`README.md` so sánh + `router.md` quy tắc chọn): `headroom` cho nén hiện tại, `claude-mem` cho timeline nhanh/session ngắn, `mempalace` cho verbatim/audit/dự án dài. Không viết code wrapper (YAGNI) cho tới khi có dự án thật.
  - Đồng bộ `AGENT.md` gốc (+ Mempalace + Memory chung), `STRUCTURE.md` (+ mempalace + shared/memory), ghi log này.
- **Bài học:** Không chạy 2 memory song song trên cùng dự án (tốn 2 worker/DB). Verbatim mempalace bù điểm yếu tóm tắt sai của claude-mem. Router markdown đủ để Agent chọn đúng lệnh mà không cần abstraction code.

### [2026-09-23] [Quyết định] Hợp nhất ui-ux-pro-max engine vào ui-taste (theo full catalog offline)
- **Bối cảnh:** Đã có `lib/ui-taste` 13 SKILL.md taste-skill. Repo `nextlevelbuilder/ui-ux-pro-max-skill` (130k★, v2.0) mang engine offline mạnh (192 reasoning rules ngành + 192 palettes + 79 styles + 74 fonts + BM25 search.py) trùng mục tiêu UI nhưng bổ sung đúng thứ taste thiếu. User chọn "hợp nhất vào ui-taste" + "full catalog offline".
- **Quyết định:** Giữ nguyên 13 skills, thêm `skills/ui-ux-pro-max.md` router + `engine/` (search.py/core.py/design_system.py + data/*.csv lõi: styles/colors/typography/products/ui-reasoning/landing/charts/ux-guidelines/app-interface/motion/icons + catalog-summary), bỏ gallery/preview/screenshots/cli/i18n. Move `engine/data` → `data` để khớp `DATA_DIR = parent.parent/data` và verify `search.py "SaaS dashboard" --domain style` OK.
- **Bài học:** Engine stdlib only, truncate 300 chars → cần --json --full. Stack version-aware (legacy rỗng nếu không match). Persist MASTER.md + pages/ overrides cho hierarchical retrieval.

### [2026-09-23] [Quyết định] Tích hợp taste-skill (anti-slop frontend) full bộ
- **Bối cảnh:** Monorepo đã đủ backend mẫu (auth/payment/notification) + memory (claude-mem/mempalace) + compression (headroom) + tối giản (ponytail) nhưng thiếu hoàn toàn module UI/frontend. Agent khi triển khai dự án ngoài sẽ sinh UI generic slop. Repo `Leonxlnx/taste-skill` (89.5k★) là bộ portable SKILL.md framework-agnostic, đúng khoảng trống.
- **Quyết định:**
  - Tạo `lib/ui-taste/` với `AGENT.md` (5 mục) + `README.md` (bảng 13 skills + 3 dials) + `skills/` chứa 13 SKILL.md fetch nguyên văn: `design-taste-frontend` (v2 experimental, mặc định), `design-taste-frontend-v1`, `gpt-taste`, `image-to-code`, `redesign-existing-projects`, `high-end-visual-design`, `full-output-enforcement`, `minimalist-ui`, `industrial-brutalist-ui`, `stitch-design-taste`, `imagegen-frontend-web/mobile`, `brandkit`.
  - Lọc bỏ `examples/` (ảnh demo nặng), `research/`, `scripts/`, `skill.sh`, `assets/` — chỉ giữ SKILL.md lõi (Ponytail Minimum).
  - Đồng bộ `AGENT.md` gốc (+ UI-taste 🟢), `STRUCTURE.md` (+ cây ui-taste/skills), ghi log này.
- **Bài học:** Ponytail: chọn 1 skill mặc định trước (YAGNI), chỉ thêm skill khác khi brief yêu cầu rõ. v2 experimental nên pin v1 nếu cần ổn định. Imagegen chỉ ra ảnh → phải qua `image-to-code` pipeline để ra code.

### [2026-09-23] [Quyết định] Triển khai cả 3 repo còn lại (cheatsheets + viking-adapter + web-automation)
- **Bối cảnh:** Sau khi user chọn "chỉ security", còn lại 3 repo đã nghiên cứu sâu (DEFER/FILTER/REFERENCE). User ra lệnh "thực thi cả 3" song song + tích hợp đầy đủ.
- **Quyết định:**
  - **Cheatsheets** (`LeCoupa/awesome-cheatsheets`, MIT): Tạo `shared/cheatsheets/` copy 8 files (php.php 15k, git.sh 9.6k, docker.sh 6.3k, nginx.sh 5.1k, mysql.sh 6.5k, bash.sh 27k, html5.html 14k, curl.sh 6.3k) + tự viết `slim-supplement.md` (Slim 4 + PHP-DI + Plates + middleware) + `README.md`. Lọc bỏ laravel.php cũ, Node/Django, React/Vue, mongo/redis, docker-machine cũ.
  - **Viking-adapter** (`volcengine/OpenViking`, AGPL-3.0): Tạo `lib/viking-adapter/` thin Guzzle (`VikingClient.php`: add_resource→poll, find/search/ls/read, auth api_key/account/user/actor_peer_id) + `composer.json` + `scripts/up.ps1` + `docker-compose.yml` (ghcr.io/volcengine/openviking:latest, ports 1933/1934). Không vendor server/crates/C++ engine — AGPL giữ ngoài.
  - **Web-automation** (`ntegrals/openbrowser`, MIT): Tạo `lib/web-automation/` PHP port (CommandCatalog + StallDetector hash + UsageMeter + HtmlToMarkdown) + `service/docker-compose.yml` sidecar + `scripts/call.ps1`. Không vendor bun/TS/Playwright core.
  - Đồng bộ `AGENT.md` gốc (+ 3 dòng 🟢), `STRUCTURE.md` (+ 3 cây + cheatsheets), `shared/memory/router.md` liên quan Viking khi scale.
- **Bài học:** Cheatsheets chỉ tham khảo, Slim-supplement bù gap; AGPL phải adapter ngoài; browser nên sidecar, không lẫn Bun vào vendor/.

### [2026-09-23] [Quyết định] Tích hợp security-audit từ vibe-security-skill (Slim adapt)
- **Bối cảnh:** Audit 4 repos cùng lúc (OpenViking DEFER AGPL, openbrowser FILTER Node, cheatsheets tham khảo). `raroque/vibe-security-skill` (MIT, 9 refs) có 70% áp thẳng Slim/PDO/JWT — đúng nhu cầu PHP vibe-coded của user. User chọn "chỉ security".
- **Quyết định:** Tạo `lib/security-audit/` fetch `SKILL.md` (7041B) + 7/9 refs (secrets/auth/rate-limit/payments/data-access/deployment/ai-integration), lọc bỏ `database-security` (Supabase RLS) + `mobile` (Expo), adapt data-access từ Prisma/Zod → PDO `prepare` + `respect/validation` + whitelist. Đồng bộ AGENT gốc (+ Security-audit 🟢), STRUCTURE (+ cây references), ghi log này.
- **Bài học:** Never trust the client — price/role/userId phải validate server-side; Slim middleware không phải sole auth; audit Minimum 3 domains trước (Secrets/Data-Access/Auth).

### [2026-09-23] [Quyết định] Triển khai PHP-stack (PHP thuần/Slim) từ awesome-php
- **Bối cảnh:** User chuyên PHP, repo `ziadoz/awesome-php` (32.7k★) đã phân tích cần thiết theo hướng PHP thuần/Slim + tham khảo curated-list.
- **Quyết định:**
  - Tạo `lib/php-stack/` với `AGENT.md` (5 mục) + `README.md` (bảng ~15 lib lõi: Slim, Plates, PHP-DI, Guzzle, Monolog, Dotenv, PHPUnit/Pest...) + `composer.json` (full) + `composer-slim.json` (minimum Slim+PHP-DI) + `.env.example`.
  - Lọc bỏ Framework nặng (Laravel/Symfony), CMS, E-commerce platform, Software/VM, Videos/Books/Podcasts — chỉ giữ bản đồ + composer mẫu (không vendor).
  - Đồng bộ `AGENT.md` gốc (PHP-stack 🟢), `STRUCTURE.md` (+ cây php-stack), ghi log này.
- **Bài học:** Bắt đầu với `composer-slim.json` (YAGNI), thêm lib khi task yêu cầu. Ưu tiên PSR, check maintain status trước khi require. Link gốc awesome-php để tra định kỳ.

### [2026-09-23] [Quyết định] Lấp gap để sẵn sàng deploy Slim + Laragon (starter + auth/payment code + PHP 8.3/CI)
- **Bối cảnh:** Review vs chuẩn Slim docs + OWASP + PHP 8.2 EOL 31-12-2026 cho thấy 3 gap chặn Full auth/payment: (1) thiếu starter chạy được (public/index.php + middleware LIFO + DI), (2) lib/auth/payment/notification chỉ placeholder 2 files, (3) PHP >=8.2 sắp EOL + thiếu CI/headers/migration.
- **Quyết định:**
  - **Starter** `lib/php-stack/starter/` (14 files): `public/index.php` (BodyParsing/Routing LIFO + ErrorMiddleware, ResponseFactory binding, .env), `config/{settings,container,database,routes,middleware,cli-config}.php` (PDO MySQL + Monolog JSON + Plates + security headers + correlation ID), `phinx.php` + 2 migrations (users/orders+transactions+idempotency_key), `templates/layout.php/home.php`, `phpunit.xml` + `HealthTest.php`, `.htaccess` + `nginx.conf.snippet` (CSP/HSTS/X-Frame), `openapi.yaml` skeleton. Verify JSON OK.
  - **Auth/payment code thật** (7 files): `lib/auth/src/{JwtService(HS256 verify, reject alg:none), AuthenticateMiddleware, AuthController(PDO prepare+whitelist), composer.json}`, `lib/payment/src/{GatewayInterface, StripeGateway(php://input+constructEvent), VnpayGateway(HMAC), PaymentService(idempotency), WebhookController, composer.json}`, `lib/notification/src/Mailer.php`.
  - **Nâng cấp** 8 composer.json `php >=8.2 → >=8.3`, `.github/workflows/build.yml` (setup-php 8.3 + MySQL 8 + composer audit + phpstan max + phpcs + pest + gitleaks), headers, Monolog JSON, OpenAPI.
- **Bài học:** Slim cần DI wiring tách file + ResponseFactory; starter là tiền đề để auth/payment copy vào `C:\laragon\www\ten-du-an` mà không viết lại.

### [2026-09-23] [Quyết định] Bổ sung JWT chuẩn + VNPay Omnipay + Rate-limit chạy được
- **Bối cảnh:** Review full-stack trước đó chỉ đủ MVP — JWT tự viết thiếu path rules, VNPay hand-rolled thiếu sort/SecureHashType/queryDR, rate-limit chỉ checklist. Đã tìm GitHub: tuupola/cube43 slim-jwt-auth, phpviet/JackieDo omnipay-vnpay, sim-soft/nikolaposa rate-limit.
- **Quyết định:**
  - **JWT** (`lib/auth`): thêm `tuupola/slim-jwt-auth ^3.0` + `cube43/slim-jwt-auth ^1.0` vào composer, tạo `JwtFirewall::create()` (path `/api`, ignore `/api/login,/health`, secure prod, error JSON, relaxed localhost) — thay middleware thô.
  - **VNPay** (`lib/payment`): thêm `omnipay/omnipay ^3.0` + `phpviet/omnipay-vnpay ^1.0` + `jackiedo/omnipay-vnpay`, tạo `OmnipayVnpayGateway` (purchase/completePurchase/fetchTransaction đã sort + hash chuẩn, thay `hash('sha256', secret+query)`).
  - **Rate-limit** (`starter`): thêm `nikolaposa/rate-limit ^1.0` + `predis/predis ^2.0` vào starter composer, tạo `RateLimitMiddleware.php` (PSR-15, Redis khi có `REDIS_HOST` + file fallback, 100 req/60s cho `/api/*` trừ `/health,/api/v1/ping`, headers `X-RateLimit-Remaining/Limit`, 429 `Retry-After`), mount trong `config/middleware.php`.
- **Bài học:** JWT cần hydrator+firewall tách lớp + LIFO order; VNPay phải Omnipay để đúng SecureHash; rate-limit file chỉ single-server, Redis cho multi-server.

### [2026-09-23] [Quyết định] Scaffold demofull (1 trang index full landing theo engine)
- **Bối cảnh:** User cho 4/5 + 5/5: `C:\laragon\www\demofull` + chỉ cần 1 trang index showcase UI/UX + JS/Animation. Chọn phong cách "theo engine đề xuất" + full landing, đã chạy `search.py "Tech SaaS developer tool" --design-system -p Demofull` → category Developer Tool/IDE, style Dark Mode OLED (#0F172A + accent #22C55E), typography JetBrains Mono/IBM Plex Sans, pattern FAQ/Documentation.
- **Quyết định:** Tạo project NGOÀI monorepo tại `C:\laragon\www\demofull` (copy starter: public/index.php + config/* + migrations + templates + composer.json + .env + phinx + openapi), persist `design-system/demofull/MASTER.md` (6026B), viết `templates/layout.php` (Tailwind CDN + GSAP + ScrollTrigger, CSS vars từ spec, reduced-motion, focus visible) + `templates/home.php` single-page full landing: nav sticky, hero + search bar prominent, social proof, popular categories, features, demo (counters/skeleton/magnetic/carousel), FAQ accordion, pricing 3 tiers, contact CTA, footer checklist. Đúng AGENT.md:98 (không tạo trong monorepo).
- **Bài học:** Engine persist MASTER.md là source of truth; single-page vẫn phải đủ checklist pre-delivery (contrast, cursor-pointer, reflow, responsive 375-1440, picsum seeds).

### [2026-09-23] [Quyết định] Landing phim 1 trang tại demofull.test (dark cinematic, static)
- **Bối cảnh:** User yêu cầu 1 trang landing website phim chỉ cần UI/UX + JS/Animation, chạy tại http://demofull.test/. Trước đó đã xóa toàn bộ `C:\laragon\www\demofull` sau lỗi vendor, cần triển khai lại dạng static để né composer/vendor.
- **Quyết định:**
  - Chạy engine `search.py "movie streaming cinema entertainment" --design-system -p Demofull` → Feature-Rich Showcase, Vibrant rose #E11D48 + blue #2563EB; chuyển sang dark cinematic #0F172A (user chọn) thay vì rose sáng, giữ contrast 4.5:1, typography Inter, gap 48px+ per spec. Persist `design-system/demofull/MASTER.md` vẫn giữ.
  - Tạo DUY NHẤT `C:\laragon\www\demofull\public\index.html` (19k, Tailwind CDN + GSAP 3.12.5 + ScrollTrigger, JetBrains Mono/Inter) với mock 12 phim + Picsum posters `picsum/seed/movie-{i}`, sections: nav sticky, hero parallax + search, trending scroll-snap row, genre chips wrap, grid 12 cards hover lift, trailer modal (focus trap, ESC), pricing 3 tiers, footer checklist. JS: counters, magnetic, carousel, search filter, modal, reduced-motion.
  - Vhost `auto.demofull.test.conf:2` DocumentRoot `.../public` + hosts entry đã fix NXDOMAIN trước đó — static nên không cần `composer install`.
- **Bài học:** Landing showcase nên static (1 file HTML) khi chỉ cần UI/UX — tránh Slim/vendor khi không dùng DB/auth/payment.

### [2026-09-23] [Quyết định] Gộp Scrapling MCP-first vào web-automation (bạn dùng nhiều)
- **Bối cảnh:** Bạn muốn 1 tính năng dùng rất nhiều từ `D4Vinci/Scrapling` (83.1k★, adaptive scraping + fetchers bypass Cloudflare + spiders concurrent + MCP/Agent Skill + markdown RAG). Đã có `lib/web-automation` (openbrowser Bun/TS sidecar) — không nên tạo lib mới trùng sidecar pattern. Bạn chọn gộp vào web-automation + trọng tâm MCP cho AI agent.
- **Quyết định:**
  - Tạo `lib/web-automation/scrapling/` với `requirements.txt` (`scrapling[fetchers,ai]`), `mcp.json` (stdio `scrapling mcp serve`, strip prompt-injection), `skill/SKILL.md` (24k) + 4 examples từ `agent-skill/Scrapling-Skill`, và `examples/` riêng (01_fetch_adaptive.py, 02_spider_pause_resume.py, 03_mcp_markdown_rag.py) hướng MCP + adaptive + RAG.
  - Thêm `service/docker-compose.scrapling.yml` (ghcr.io/d4vinci/scrapling) song song với openbrowser compose (chọn 1 sidecar mỗi task), và `src/ScraplingClient.php` thin Guzzle/CLI bridge cho Slim (mirror VikingClient).
  - Cập nhật `lib/web-automation/AGENT.md` (+ Scrapling MCP-first vs openbrowser vs Panther native) + `README.md` (bảng chọn), `AGENT.md` gốc (+ Scrapling MCP trong web-automation), `STRUCTURE.md` (+ scrapling/), ghi log này. Ponytail: không vendor scrapling source — chỉ instruction/thin client.
- **Bài học:** MCP strip hidden text → agent đọc ít hơn, rẻ hơn, an toàn hơn — bù cho openbrowser raw + headroom tiết kiệm token. Adaptive `auto_save`/`adaptive=True` đúng nhu cầu site đổi layout.

### [2026-09-23] [Quyết định] Tool cao-shopee.php ngang hàng index (browser fetch + mock khi 403)
- **Bối cảnh:** User yêu cầu tool demo cào Shopee (Tên+giá+đã bán) chạy trên trình duyệt, nằm ngang hàng `index.html` tại `http://demofull.test/`. Trước đó `public/` chỉ có static `index.html` và Nginx vhost thiếu block `\.php$` → PHP không chạy.
- **Quyết định:**
  - Tạo `C:\laragon\www\demofull\public\cao-shopee.php` (PHP thuần, cURL, không Composer): priming cookies homepage → GET `api/v4/search/search_items?match_id=11035954` (category Laptop), extract `name`, `price/100000`, `sold ?? historical_sold`, render bảng Tên+giá+đã bán + sort/filter JS. Khi `403/90309999` (đã chứng minh) hiện banner đỏ kèm `tracking_id` + 12 sp mẫu DEMO để UI vẫn demo.
  - Sửa `C:\laragon\etc\nginx\sites-enabled\auto.demofull.test.conf` thêm `index index.html index.php` + `location ~ \.php$ { fastcgi_pass php_upstream }`, reload Nginx từ `C:\laragon\bin\nginx\nginx-1.28.2` (Set-Location rồi `nginx -s reload`), verify `curl -H Host:demofull.test http://127.0.0.1/cao-shopee.php` → 200 + DEMO (403).
- **Bài học:** Static vhost phải thêm PHP block mới chạy `.php`; Shopee SGW type 4 chặn mạnh → tool phải có fallback mock để không trắng trang.

### [2026-09-23] [Quyết định] Thêm XPath vào cao-shopee + demo trang tĩnh (Scrapling page.xpath ↔ DOMXPath)
- **Bối cảnh:** User hỏi cào bằng XPath, chọn "trang tĩnh khác" (không phải Shopee SPA). Scrapling docs cho thấy `page.xpath()` cùng API với `page.css()` + adaptive, còn PHP có `DOMXPath` stdlib. Đã nghiên cứu selection.md + adaptive docs.
- **Quyết định:**
  - Thêm mode `xpath` vào `C:\laragon\www\demofull\public\cao-shopee.php`: form mới (url + 3 XPath Tên/Giá/Đã bán, mặc định `quotes.toscrape.com` với `//span[@class="text"]/text()`...), hàm `xpath_fetch()` (cURL → DOMDocument → DOMXPath, validate Invalid XPath, gom max count items, filter empty), tách logic Shopee vs XPath, chỉ mock cho Shopee bị chặn. Thêm tabs mode shopee/xpath.
  - Tạo `lib/web-automation/scrapling/examples/06_xpath_static.py` demo Fetcher `page.xpath()`, chain, adaptive, generate selectors — verify `Fetcher.get(quotes.toscrape) → 10 items CSS/XPath` đều 10, sample author/tags OK.
  - Verify `curl -H Host:demofull.test http://127.0.0.1/cao-shopee.php?mode=xpath&url=quotes...` → "XPath live: 10 items" (200). Cập nhật AGENT.md/README.md web-automation.
- **Bài học:** XPath chạy trực tiếp trên HTML tĩnh (không cần browser), SPA như Shopee thì phải API/Stealthy; Scrapling `auto_save`/`adaptive` áp dụng cho cả XPath.

### [2026-09-23] [Quyết định] Sửa cao-shopee bằng XPath từ HTML thực tế (card li)
- **Bối cảnh:** User cung cấp HTML thực tế (`shopee.txt` 5861B, 1 li) với Tên `div.line-clamp-2`, Giá `span.text-base/5.font-medium` (158.000), Đã bán `div.text-shopee-black87.ml-auto` (60k+), cùng file `shopee.txt` trước đó chỉ là vỏ SPA 249KB không có sản phẩm.
- **Quyết định:**
  - Thêm mode `xpath-shopee` vào `C:\laragon\www\demofull\public\cao-shopee.php`: hàm `shopee_xpath_local()` đọc `shopee.txt` local, `DOMXPath` card `//li[contains(@class,"shopee-search-item-result__item")]`, per-card `.//div[contains(@class,"line-clamp-2")]` (Tên), `.//span[contains(@class,"text-base")]` (Giá 158.000→158000), `.//div[contains(@class,"text-shopee-black87")]` (60k+→60000), parse shop/item từ `href -i.shop.item`, ảnh, hiển thị cùng bảng sort/filter; tách tabs Shopee/XPath/XPath Shopee.
  - Tạo `lib/web-automation/scrapling/examples/07_shopee_xpath.py` (Selector adaptive, xpath per-card) — verify 1 card Tên đúng, giá 158000, sold 60000; curl `?mode=xpath-shopee` → 200 + "XPath Shopee (file): 1 items".
- **Bài học:** Shopee SPA cần HTML render (li grid) mới XPath được; API 403 phải fallback file local per-card, chuẩn hóa `k+` và `xxx.xxx`.

### [2026-09-23] [Quyết định] Xóa sạch Shopee (demo) — thay bằng tool XPath tổng quát
- **Bối cảnh:** User yêu cầu loại bỏ mọi thông tin Shopee (chỉ là demo). Trước đó monorepo có 3 files `04/05/07_shopee*` + `C:\laragon\www\demofull\public\cao-shopee.php` (3 mode shopee/xpath/xpath-shopee) + `shopee.txt` (5861B), cùng nhiều dòng nhắc trong AGENT/README.
- **Quyết định:**
  - Xóa `04_shopee_laptop.py`, `05_shopee_proxy_stealth.py`, `07_shopee_xpath.py` (giữ 06_xpath_static), xóa `cao-shopee.php` + `shopee.txt` ở demofull.
  - Tạo `cao-xpath.php` mới (PHP DOMXPath thuần, form url + 3 XPath, không Shopee) — verify `curl Host:demofull.test /cao-xpath.php` → 200 + "XPath live: 10 items" từ quotes.toscrape.com.
  - Sửa `06_xpath_static.py` (bỏ dòng Shopee SPA), `AGENT.md:71` + `README.md:45` (đổi link sang `cao-xpath.php`), giữ 3 logs cũ trong EXPERIENCE làm lịch sử.
- **Bài học:** Demo phải tách khỏi dữ liệu nhạy cảm nền tảng; XPath tổng quát đủ để tái dùng mà không vướng anti-bot.

### [2026-09-23] [Quyết định] Dọn dẹp — xóa dữ liệu quét tạm + demo demofull (giữ monorepo)
- **Bối cảnh:** User yêu cầu không còn nhu cầu Happyskin, chọn "Cả hai" (xóa cả file tạm quét + demo ngoài). Trước đó đã tạo nhiều file tạm quét HappySkin + Shopee + demo landing phim/XPath.
- **Quyết định:**
  - Xóa nhóm A (tạm): `tool_0d2a208da001pxOacX07cJ5orO` (dump HappySkin 117KB), `shopee_probe.py`, `shopee_stealth.py`, `shopee_html.py`, `test_shopee_xpath.py`, `demofull-spec.json`, `shopee_cookies.txt` (cả AppData\Local\Temp và Windows\Temp).
  - Xóa nhóm B (demo): `C:\laragon\www\demofull\public\index.html` (19k landing phim) + `cao-xpath.php` (7.5k XPath tool) — `public/` nay rỗng, `curl demofull.test` → 403 Forbidden (đúng do thiếu index).
  - Giữ nguyên `monorepo-root` (lib 158 files, shared/cheatsheets, examples 06_xpath_static) và vhost `auto.demofull.test.conf` + `hosts` entry để tái dùng.
- **Bài học:** Tách rõ tài sản lâu dài (monorepo) vs dữ liệu tạm (tool-output, Temp) vs demo ngoài — dọn theo nhóm, giữ vhost để không phải fix DNS lại.

### [2026-09-23] [Quyết định] Đóng gói monorepo-20260923.zip (full kèm .git)
- **Bối cảnh:** User yêu cầu làm sạch để đóng gói, chọn full kèm `.git` + mặc định tên/lưu ngoài `monorepo-root`. Trước đó đã dọn `__pycache__`, demo ngoài rỗng, ngoài monorepo chỉ còn 2 vhost + hosts.
- **Quyết định:**
  - Verify `git status` clean, grep `shopee` active code về 0 (chỉ còn log lịch sử EXPERIENCE), `__pycache__` clean, lib 12 modules đủ.
  - Ghi log này + commit, nén `D:\MarkLeo\Repo\monorepo-20260923.zip` (kèm `.git`, giữ `.github/workflows`, loại trừ rác hệ thống Thumbs.db/desktop.ini), verify SHA256 + đủ lib/shared/4 docs.
- **Bài học:** Đóng gói phải ngoài monorepo để tránh vòng lặp, kèm `.git` giữ lịch sử, `.gitignore` đã cover `__pycache__/vendor/node_modules`.

---

<!-- Thêm log mới ở trên dòng này, giữ log cũ bên dưới để dễ tra cứu -->
