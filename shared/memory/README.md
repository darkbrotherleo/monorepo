# shared/memory — Lớp Memory Chung (Abstraction)

> Gộp `lib/claude-mem` + `lib/mempalace` thành 1 interface duy nhất cho Agent. Ponytail: YAGNI — chỉ markdown quy ước, chưa viết code wrapper cho tới khi có dự án thật cần.

## Khi nào dùng backend nào?

| Nhu cầu | Chọn | Vì sao |
|---------|------|--------|
| Session ngắn, cần timeline nhanh, tiết kiệm token, gắn OpenCode/Claude Code | **claude-mem** | Nhẹ, 3 MCP tools (`search/timeline/get_observations`), progressive disclosure |
| Cần recall **chính xác nguyên văn** (quyết định, bugfix chi tiết, audit) | **mempalace** | Verbatim, không tóm tắt, 96.6% R@5 raw LongMemEval, 45 MCP tools + knowledge-graph |
| Dự án dài, nhiều wings/projects, cần scoping theo people/topics | **mempalace** | Wings → Rooms → Drawers, backend pluggable |
| Chỉ cần nén context hiện tại, không cần lưu dài hạn | **headroom** (không phải memory) | Proxy compression, không lưu persistent |

Không chạy 2 memory cùng lúc trên cùng 1 dự án (tốn 2 worker/DB/port).

## Quy ước chung
- `lib/claude-mem` và `lib/mempalace` là 2 implementation, `shared/memory` là router quy ước.
- Agent đọc `router.md` trước khi chọn lệnh `search`/`mine`/`wake-up`.
- Cài đặt: `lib/claude-mem/scripts/install.ps1` (npx) và `lib/mempalace/scripts/install.ps1` (uv tool).

## Tham chiếu
- `lib/claude-mem/AGENT.md` — MCP: search/timeline/get_observations
- `lib/mempalace/AGENT.md` — CLI: mine/search/wake-up/sweep, 45 MCP tools
- `shared/memory/router.md` — quy tắc chọn chi tiết (đọc trước khi hành động)
