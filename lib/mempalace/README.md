# lib/mempalace — Local-first AI Memory (Verbatim)

Lưu nguyên văn lịch sử hội thoại, retrieval bằng semantic search. Không tóm tắt, không paraphrase.

## Cài đặt
```bash
# Khuyến nghị — isolated, tránh PEP 668
uv tool install mempalace
mempalace init ~/projects/myapp

# Thay thế
pipx install mempalace
# hoặc trong venv: pip install mempalace

# Docker (multi-arch amd64+arm64)
docker pull ghcr.io/mempalace/mempalace:latest
docker run -i --rm -v mempalace-data:/data ghcr.io/mempalace/mempalace
```

Lần đầu cần mạng để tải embedding model (~80MB minilm / ~300MB embeddinggemma).

## Dùng nhanh
```bash
mempalace mine ~/projects/myapp                    # mine files
mempalace mine ~/.claude/projects/ --mode convos   # mine sessions
mempalace search "why did we switch to GraphQL"
mempalace wake-up                                   # load context cho session mới
mempalace sweep <transcript-dir>                    # per-message verbatim
```

## Backend
ChromaDB mặc định (không cấu hình). Đổi qua `MEMPALACE_BACKEND` hoặc `--backend`:
`sqlite_exact | rust_exact | milvus | qdrant | pgvector`. Xem `lib/mempalace/AGENT.md`.

## Tích hợp MCP / Hooks
- MCP: 45 tools (palace, knowledge-graph, agent diaries, logstream) — xem `mempalaceofficial.com/reference/mcp-tools`
- Hooks: auto-save cho Claude Code/Codex/Cursor — `guide/hooks` + `guide/cursor-hooks`

## Khi nào dùng vs claude-mem?
Xem `shared/memory/README.md` và `shared/memory/router.md` — quy tắc chọn backend trong memory chung.

---
*Nguồn: MemPalace/mempalace — 96.6% R@5 raw LongMemEval (zero API calls). Đã lọc bỏ crates/benchmarks/landing/website khi tích hợp.*
