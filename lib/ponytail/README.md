# lib/ponytail — Tư Duy Tối Giản

Module này không chứa code thực thi phức tạp, mà chứa **bộ quy tắc tư duy** cho Agent và các file cấu hình cho IDE.

## Cách Sử Dụng
1.  **Cho Agent:** Đọc `AGENT.md` trong thư mục này để "huấn luyện" tư duy lười biếng.
2.  **Cho Dự án ngoài:** Copy file tương ứng trong thư mục `rules/` vào root của dự án của bạn:
    - `.cursorrules` -> Nếu dùng Cursor.
    - `.clinerules` -> Nếu dùng Cline.
    - `AGENT.md` -> Cho mọi AI Agent khác.

## Triết Lý
"Code tốt nhất là code chưa bao giờ được viết." - Tiết kiệm thời gian, giảm bug, dễ bảo trì.

## Cấu Trúc
- `AGENT.md`: Quy tắc cốt lõi.
- `rules/`: File cấu hình cho các công cụ AI.
- `src/`: Các script bổ trợ (nếu có trong tương lai).

---
*Tích hợp ngày: 2026-09-23*
