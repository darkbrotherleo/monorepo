# shared/ — Code Dùng Chung

Chứa code share giữa nhiều tính năng trong `lib/` và dự án ngoài.

## Quy Ước
- Chỉ chứa code **không gắn với nghiệp vụ cụ thể** của một feature.
- `lib/*` được phép import từ `shared/`, nhưng `lib/*` không phụ thuộc chéo nhau.

## Cấu Trúc Dự Kiến
```
shared/
├── README.md       # File này
├── helpers/        # Hàm tiện ích (formatDate, slugify, ...)
├── utils/          # Utils chung (validator, crypto, ...)
├── constants/      # Hằng số, enum, error codes
├── types/          # TypeScript types / interfaces chung
└── middleware/     # Middleware chung (logger, errorHandler, cors)
```

## Sử Dụng
Copy toàn bộ hoặc từng phần cần thiết vào dự án:

```js
const { formatDate } = require('./shared/helpers/date');
const { ERROR_CODES } = require('./shared/constants/errors');
```

## Lưu Ý
- Không đặt logic nghiệp vụ đặc thù ở đây.
- Khi thêm file mới, cập nhật mô tả trong file này.

---
*Teams: mẫu khởi tạo — thêm code khi có nhu cầu thực tế.*
