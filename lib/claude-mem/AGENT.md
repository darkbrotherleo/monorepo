# AGENT.md — lib/claude-mem

## Sử dụng Bộ nhớ Bền vững (Claude-Mem)

Module này hướng dẫn Agent cách tận dụng Claude-Mem để duy trì và truy xuất kiến thức qua các phiên làm việc.

### 1. Kích hoạt Bộ nhớ Bền vững

*   **Trước khi bắt đầu phiên làm việc mới**:
    *   **Cài đặt**: Đảm bảo `claude-mem` đã được cài đặt. Nếu chưa, chạy `npx claude-mem install` (hoặc script `install.ps1` trong module này). Nếu chạy `npx claude-mem install` lần đầu, nó sẽ tự động cấu hình worker service.
    *   **Cấu hình**: Chỉnh sửa file `~/.claude-mem/settings.json` để tùy chỉnh các tham số như `provider`, `port`, `mode`. Xem ví dụ trong `README.md` hoặc [tài liệu chính thức](https://docs.claude-mem.ai/configuration).

### 2. Truy xuất Kiến thức (MCP Search Tools)

Claude-Mem cung cấp 3 công cụ MCP chính để truy vấn bộ nhớ:

1.  **`search(query, type, date, project, limit)`**: Tìm kiếm các quan sát (observations) dựa trên từ khóa, loại (bugfix, decision, etc.), ngày tháng, dự án hoặc giới hạn số lượng kết quả.
2.  **`timeline(query, id)`**: Lấy context thời gian xung quanh một quan sát cụ thể hoặc một truy vấn.
3.  **`get_observations(ids)`**: Truy xuất chi tiết đầy đủ của các quan sát dựa trên danh sách ID.

**Ví dụ sử dụng trong Agent (Python):**

```python
# Giả định bạn đã khởi tạo client Claude-Mem hoặc có các hàm tương ứng
# Ví dụ tìm các bug liên quan đến 'authentication' trong dự án hiện tại
search_results = claude_mem_client.search(query="authentication bug", type="bugfix", project="current")

# Nếu tìm thấy ID thú vị (ví dụ: #123), lấy timeline
if search_results and search_results[0].id:
    timeline_context = claude_mem_client.timeline(id=search_results[0].id)

# Lấy chi tiết của các bug quan trọng
relevant_ids = [obs.id for obs in search_results if obs.priority == 'high']
if relevant_ids:
    full_details = claude_mem_client.get_observations(ids=relevant_ids)
```

### 3. Nguyên tắc chung

*   Ưu tiên sử dụng bộ nhớ có sẵn thay vì tự tạo lại thông tin.
*   Khi cần truy xuất thông tin quá khứ, hãy sử dụng các công cụ `search`, `timeline`, `get_observations` một cách hợp lý để tiết kiệm token và tăng tốc độ xử lý.
*   Luôn cập nhật `EXPERIENCE.md` qua `headroom learn` (hoặc quy trình tương tự) để chia sẻ kiến thức và bài học kinh nghiệm.

---
*Hướng dẫn sử dụng: 2026-09-23*
