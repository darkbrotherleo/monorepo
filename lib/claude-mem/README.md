# lib/claude-mem — Bộ nhớ Bền vững cho Agent

Module này cung cấp hướng dẫn và script để Agent của chúng ta có thể tận dụng **Claude-Mem**, một hệ thống bộ nhớ bền vững, giúp duy trì ngữ cảnh qua các phiên làm việc.

## 1. Mục Đích

Claude-Mem cho phép Agent ghi nhớ thông tin từ các phiên trước, giúp duy trì sự liên tục, hiểu rõ hơn về dự án và tránh lặp lại các công việc đã làm.

## 2. Cài Đặt Claude-Mem

Claude-Mem có thể được cài đặt qua `npm` hoặc `npx`. Chúng tôi cung cấp script PowerShell để tự động hóa quá trình này.

**Lệnh cài đặt chính:**

```bash
# Sử dụng npx để cài đặt (khuyến nghị):
npx claude-mem install

# Hoặc cài đặt qua npm:
npm install -g claude-mem
```

**Lưu ý**: Việc cài đặt có thể yêu cầu Node.js và npm trên hệ thống của bạn.

## 3. Sử Dụng Claude-Mem

### 3.1 Khởi động Worker

Sau khi cài đặt, bạn cần chạy worker service để Claude-Mem hoạt động. Worker này cung cấp API cho Agent truy cập bộ nhớ.

```bash
# Chạy worker trên port 8787 (mặc định)
claude-mem worker --port 8787
```

### 3.2 Cấu hình Agent

Agent cần được cấu hình để gửi và nhận context từ Claude-Mem worker. Các biến môi trường hoặc file cấu hình (`~/.claude-mem/settings.json`) có thể được sử dụng.

Xem thêm tại [Official Documentation](https://docs.claude-mem.ai/configuration).

### 3.3 Sử dụng MCP Search Tools

Agent có thể truy vấn bộ nhớ bằng các công cụ MCP:

-   `search(query, type, date, project, limit)`: Tìm kiếm quan sát.
-   `timeline(query, id)`: Lấy context thời gian.
-   `get_observations(ids)`: Lấy chi tiết quan sát.

Xem [Search Tools Guide](https://docs.claude-mem.ai/usage/search-tools) để biết thêm chi tiết.

## 4. Tích hợp vào Monorepo

-   **AGENT.md (Root)**: Đã cập nhật để bao gồm `claude-mem` trong mapping và hướng dẫn Agent kích hoạt bộ nhớ bền vững.
-   **EXPERIENCE.md**: Đã ghi nhận quyết định tích hợp.

## 5. Lời khuyên

*   Ưu tiên sử dụng bộ nhớ có sẵn để tránh lặp lại công việc.
*   Khi cần truy xuất thông tin quá khứ, hãy sử dụng các công cụ `search`, `timeline`, `get_observations` một cách hợp lý.

---
*Tài liệu tham khảo: 2026-09-23*
