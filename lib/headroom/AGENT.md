# AGENT.md — lib/headroom

## Mục Đích
Context compression layer cho Agent: nén tool outputs, logs, RAG chunks, files trước khi gửi LLM. Giảm 20% token cho coding agent, 60-95% cho JSON. Chạy local, không gửi prompt ra ngoài. Nguồn: `headroomlabs-ai/headroom`.

## Kiến Trúc / File Chính
```
lib/headroom/
├── AGENT.md        # File này
├── README.md       # Hướng dẫn cài + 4 chế độ (library/proxy/wrap/MCP)
├── scripts/
│   ├── install.ps1 # pip install headroom-ai[all]
│   └── wrap.ps1    # headroom proxy --port 8787 + headroom wrap opencode
```
Upstream thực: `crates/` (Rust), `headroom/` (Python), `sdk/typescript`, `plugins/`. Trong monorepo chỉ giữ instruction + scripts (không copy code nguồn — cài qua pip).

Pipeline: `CacheAligner → ContentRouter → SmartCrusher/CodeCompressor/Kompress-v2-base → CCR (reversible)`. Hỗ trợ cross-agent memory + `headroom learn`.

## Cách Tích Hợp Vào Dự Án
1. Cài: `pip install "headroom-ai[all]"` hoặc `uv tool install --python 3.13 "headroom-ai[all]"` (chạy `lib/headroom/scripts/install.ps1`).
2. Chạy proxy: `headroom proxy --port 8787` — hoặc `.\scripts\wrap.ps1` để auto wrap `opencode` (cũng hỗ trợ `claude|codex|cursor|aider|copilot|...`).
3. Hoặc inline library: `from headroom import compress; result = compress(messages, model="gpt-4o")`.
4. MCP client: `headroom mcp install` → tools `headroom_compress / headroom_retrieve / headroom_stats`.
5. Sau task lớn: `headroom learn --apply` → tự ghi bài học vào `EXPERIENCE.md` (đã được bạn cho phép).
6. Kiểm tra: `headroom doctor`, `headroom perf`, `headroom dashboard`.

Copy `lib/headroom/scripts/*` ra dự án ngoài hoặc cài trực tiếp qua pip — không cần copy cả `lib/`.

## Dependencies / Yêu Cầu
- Python 3.10+, Node (cho wrap), Rust toolchain chỉ khi build từ source.
- Extras: `[proxy] [mcp] [ml] [code] [memory]` — `[all]` đã bao gồm core nhưng chưa gồm `[agno]/[langchain]` adapters.
- Không cần API key cho path benchmark. Beacon telemetry bật mặc định, tắt bằng `HEADROOM_BEACON=off`.

## Lưu Ý / Bài Học
- Chưa cài trên máy này (`headroom`/`uv` not found — đã kiểm tra 2026-09-23). Cài trước khi dùng.
- Chỉ hiệu quả với session dài + tool output nặng. Payload ngắn/prose dày thì gần như không nén.
- `headroom proxy` đọc `HEADROOM_OUTPUT_SHAPER` / `HEADROOM_TLS_STRICT` live mỗi request.
- Ponytail: dùng Headroom thay vì tự viết nén (Reuse > Minimum). Proxy là one-line, không tự chế.

---
*Cập nhật: 2026-09-23 — Tích hợp headroomlabs-ai/headroom (chỉ instruction + scripts)*
