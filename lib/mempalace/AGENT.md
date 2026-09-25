# AGENT.md — lib/mempalace

## Mục Đích
Local-first AI memory: lưu **verbatim** (nguyên văn) lịch sử hội thoại, không tóm tắt/paraphrase, retrieval bằng semantic search. Benchmark 96.6% R@5 raw trên LongMemEval (zero API calls), 98.4% hybrid, 99%+ với rerank. Nguồn: `MemPalace/mempalace`.

## Kiến Trúc / File Chính
```
lib/mempalace/
├── AGENT.md        # File này
├── README.md       # Hướng dẫn cài + mine/search/wake-up
└── scripts/
    └── install.ps1 # uv tool install mempalace → mempalace init
```
Cung (palace) = wings (people/projects) → rooms (topics) → drawers (nội dung gốc). Retrieval pluggable: mặc định ChromaDB, interface tại `mempalace/backends/base.py` — có thể thay backend không chạm logic khác. Lưu ý đã lọc: **không** copy `crates/` (Rust native), `benchmarks/`, `landing/`, `website/`, `deploy/` GPU — chỉ giữ instruction (đúng quy tắc mục 4 AGENT gốc).

Thêm: knowledge-graph temporal (validity windows), 45 MCP tools (palace/KG/agent diaries/logstream), auto-save hooks cho Claude Code/Codex/Cursor.

## Cách Tích Hợp Vào Dự Án
1. Cài: `uv tool install mempalace` (khuyến nghị, isolated) hoặc `pipx install mempalace` hoặc `pip install mempalace` trong venv. Chạy `.\scripts\install.ps1`.
2. Khởi tạo: `mempalace init ~/projects/myapp` (tạo palace riêng) hoặc hub mode cho team.
3. Mine dữ liệu:
   ```bash
   mempalace mine ~/projects/myapp                    # file dự án
   mempalace mine ~/.claude/projects/ --mode convos   # sessions Claude Code
   mempalace sweep <transcript-dir>                   # per-message verbatim, idempotent
   ```
4. Dùng:
   ```bash
   mempalace search "why did we switch to GraphQL"
   mempalace wake-up                                   # nạp context cho session mới
   ```
5. MCP: gắn stdio server vào Claude Code / MCP client (xem `docs/mempalaceofficial.com/reference/mcp-tools`).
6. Hooks: cài auto-save theo `guide/hooks` để session 30 ngày không mất.
7. Qua `shared/memory/router.md` để chọn backend: cần **verbatim chính xác → mempalace**, cần timeline nhanh/nhẹ → claude-mem.

Copy `lib/mempalace/scripts/*` ra dự án ngoài hoặc cài trực tiếp — không cần copy cả `lib/`.

## Dependencies / Yêu Cầu
- Python 3.9+, ~300MB cho embedding model (`embeddinggemma-300m` multilingual khuyến nghị hoặc `all-MiniLM-L6-v2` English-only 30MB). Lần đầu cần mạng để tải model.
- Vector backend: ChromaDB mặc định (bundled). Opt-in: `sqlite_exact`, `rust_exact` (wheel/native), `milvus`, `qdrant`, `pgvector` qua `MEMPALACE_BACKEND` / `--backend`.
- Optional: Docker `ghcr.io/mempalace/mempalace:latest` với volume `/data`.
- Không cần API key cho path benchmark. Có thể offload embedding qua `embedding_model: "openai-compat"` + `embedding_api_url`.

## Lưu Ý / Bài Học
- Verbatim = không mất thông tin do tóm tắt sai — ưu thế khi cần recall chính xác (quyết định, bugfix chi tiết).
- Ponytail: chọn backend theo Ladder — YAGNI: chỉ mine scope cần thiết (`--wing` per project), Reuse backend có sẵn thay vì tự viết retrieval.
- Đổi embedding model phải `mempalace repair rebuild-index` (khác vector space).
- Cảnh giác domain giả mạo — chỉ tin `github.com/MemPalace/mempalace`, PyPI, `mempalaceofficial.com`.

---
*Cập nhật: 2026-09-23 — Tích hợp MemPalace/mempalace (chỉ instruction + scripts, gộp memory chung với claude-mem)*
