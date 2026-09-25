# router.md — Quy tắc chọn backend Memory

> Agent đọc file này trước khi gọi bất kỳ lệnh memory nào. Không viết code wrapper — chỉ chọn lệnh đúng.

## Quyết định nhanh (theo Ladder)

```
1. Chỉ cần nén context hiện tại, không lưu lâu dài? → dùng headroom (proxy), không đụng memory.
2. Cần timeline/progressive disclosure nhanh, token rẻ? → claude-mem
3. Cần verbatim nguyên văn, audit chính xác, scoping wings/rooms? → mempalace
4. Chưa rõ? → mặc định claude-mem cho session < 1 ngày, mempalace cho dự án > 1 tuần
```

## Mapping lệnh

| Ý định | claude-mem | mempalace |
|--------|-----------|-----------|
| Lưu dữ liệu | auto via hooks (capture tool-use) | `mempalace mine <path> [--mode convos]`, `mempalace sweep <transcript>` |
| Tìm kiếm | `search(query, type, date, project)` | `mempalace search "query"` |
| Lấy context quanh kết quả | `timeline(id/query)` | (dùng search + knowledge-graph timeline) |
| Lấy chi tiết | `get_observations(ids)` | drawer content (verbatim) |
| Nạp cho session mới | auto inject | `mempalace wake-up` |
| Xem live | web viewer tại worker URL | `mempalace` CLI + MCP 45 tools |

## Quy tắc
- **Không chạy song song** 2 worker memory trên cùng 1 dự án.
- Khi đổi backend, mine lại từ nguồn gốc (`~/.claude/projects/` hoặc `~/projects/<app>`).
- Đổi embedding model mempalace → phải `mempalace repair rebuild-index`.
- Sau task lớn: `headroom learn --apply` để ghi EXPERIENCE.md (đã được phép), không tự ghi đè memory.

## Ví dụ
```bash
# Cần tìm bugfix verbatim về auth:
mempalace search "auth bug fix"

# Cần timeline nhanh quanh 1 observation:
# (nếu đang dùng claude-mem)
search(query="auth bug", type="bugfix"); timeline(id=123); get_observations(ids=[123])
```
