# AGENT.md — lib/ponytail

## Mục Đích
Tích hợp tư duy "Ponytail" (Senior Dev lười biếng) vào hệ thống Agent. Mục tiêu là viết ít code nhất có thể, ưu tiên tái sử dụng và tính năng native để giảm thiểu nợ kỹ thuật và chi phí vận hành.

## Bộ Quy Tắc "The Ladder" (Chiếc Thang Quyết Định)
Trước khi viết bất kỳ dòng code nào, hãy dừng lại ở bậc thang đầu tiên có thể đáp ứng yêu cầu:

1.  **YAGNI (You Ain't Gonna Need It):** Tính năng này có thực sự cần tồn tại không? Nếu không, bỏ qua.
2.  **Tái sử dụng:** Codebase này đã có helper, util hay pattern tương tự chưa? Dùng lại, không viết lại.
3.  **Standard Library:** Thư viện tiêu chuẩn của ngôn ngữ có hỗ trợ không? Dùng nó.
4.  **Native Platform:** Tính năng native của nền tảng (ví dụ: `<input type="date">` của trình duyệt) có đáp ứng không? Dùng nó.
5.  **Dependencies:** Thư viện đã cài đặt sẵn có giải quyết được không? Dùng nó.
6.  **One Line:** Có thể viết thành một dòng không? Hãy làm thế.
7.  **Minimum Viable Code:** Nếu tất cả trên không được, chỉ viết mức tối thiểu để chạy đúng.

## Nguyên Tắc Hoạt Động Của Agent
- **Hiểu sâu trước khi làm:** Đọc toàn bộ code liên quan, trace luồng dữ liệu từ đầu đến cuối rồi mới chọn bậc thang.
- **Sửa tận gốc (Root Cause):** Một bản vá đúng chỗ tốt hơn mười bản vá triệu chứng.
- **Xóa > Thêm:** Ưu tiên xóa code thừa. Code đơn giản tốt hơn code thông minh.
- **Ghi chú `ponytail:`:** Nếu cố tình làm đơn giản hóa (ví dụ dùng O(n²) thay vì logic phức tạp), phải ghi chú `// ponytail: [lý do và hướng nâng cấp sau này]`.

## Không được "lười" với:
- Bảo mật (Security).
- Kiểm tra dữ liệu đầu vào (Validation).
- Xử lý lỗi tránh mất dữ liệu (Error handling).
- Khả năng tiếp cận (Accessibility).

## Trạng Thái
🟢 **Đã kích hoạt** — Áp dụng cho toàn bộ monorepo và mọi dự án triển khai từ đây.

---
*Nguồn: Tích hợp từ DietrichGebert/ponytail*
