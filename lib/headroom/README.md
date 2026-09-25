# lib/headroom — Tối Ưu Hóa Context Agent

Module này cung cấp các chỉ dẫn và script để Agent của chúng ta có thể tận dụng công cụ **Headroom** nhằm tối ưu hóa việc sử dụng token LLM.

## 1. Mục Đích

Headroom giúp nén dữ liệu đầu vào (outputs, logs, files) trước khi gửi đến LLM, giúp tiết kiệm chi phí và tăng tốc độ xử lý mà không làm giảm chất lượng câu trả lời.

## 2. Cài Đặt Headroom

Headroom được khuyến nghị cài đặt bằng `pip` (hoặc `uv` nếu có).

```bash
# Khuyến nghị cài đặt với Python 3.13+
# Sử dụng pip:
pip install "headroom-ai[all]"

# Hoặc sử dụng uv:
# uv tool install --python 3.13 "headroom-ai[all]"
```

## 3. Sử Dụng Headroom

### 3.1 Chế độ Proxy (Khuyến nghị cho Agent):
Headroom có thể hoạt động như một proxy chặn mọi request LLM. Agent sẽ tự động gửi request qua proxy này.

```bash
# Chạy proxy trên port 8787 (mặc định)
headroom proxy --port 8787
```

### 3.2 Chế độ Wrap (Tự động cấu hình Agent):
Lệnh này giúp tự động cấu hình Agent để sử dụng Headroom proxy và các tính năng đi kèm.

```bash
# Ví dụ cho OpenCode:
headroom wrap opencode

# Cho các agent khác:
# headroom wrap claude
# headroom wrap cursor
# ... xem thêm trong AGENT.md
```

### 3.3 Chế độ Library (Sử dụng trực tiếp trong code):
Nếu bạn cần tích hợp sâu hơn, có thể import hàm `compress` trực tiếp.

```python
# Python
from headroom import compress

# TypeScript
# import { compress } from 'headroom-ai'
```

## 4. Học hỏi Tự động (`headroom learn`)

Sau mỗi phiên làm việc, Headroom có thể phân tích kết quả và tự động cập nhật các "bài học" vào file `EXPERIENCE.md` (hoặc file khác tùy cấu hình).

```bash
# Kích hoạt chế độ học hỏi và áp dụng:
headroom learn --apply
```

## 5. Kiểm tra & Giám sát

```bash
# Kiểm tra trạng thái hoạt động:
headroom status

# Xem thông tin hiệu suất (sau khi proxy đã chạy):
headroom perf

# Mở dashboard theo dõi savings (nếu proxy đang chạy):
headroom dashboard
```

---
*Tài liệu tham khảo: 2026-09-23*
