# engine/usage.md — Cách dùng offline engine

Engine đã verify: `python3 lib/ui-taste/engine/search.py "SaaS dashboard" --domain style --json` OK.

## Lệnh chính
```bash
# Design System đầy đủ (ASCII mặc định)
python3 lib/ui-taste/engine/search.py "beauty spa wellness" --design-system -p "Serenity Spa"

# Markdown + persist
python3 lib/ui-taste/engine/search.py "SaaS dashboard" --design-system -p "MyApp" --persist --output-dir "/path/to/project"
python3 lib/ui-taste/engine/search.py "SaaS dashboard" --design-system -p "MyApp" --persist --output-dir "/path/to/project" --page "dashboard"

# Domain search
python3 lib/ui-taste/engine/search.py "glassmorphism" --domain style --json
python3 lib/ui-taste/engine/search.py "elegant serif" --domain typography --json
python3 lib/ui-taste/engine/search.py "dashboard" --domain chart --json
python3 lib/ui-taste/engine/search.py "error summary validation" --domain ux --json

# Stack (Laravel cho chuyên môn PHP của bạn)
python3 lib/ui-taste/engine/search.py "form validation" --stack laravel --json
python3 lib/ui-taste/engine/search.py "responsive layout" --stack html-tailwind --json
```

## Thay đổi dials (1-10, chỉ với --design-system)
```bash
python3 lib/ui-taste/engine/search.py "SaaS dashboard" --design-system -p "MyApp" --variance 8 --motion 9 --density 7
```

## Lưu ý truncate
Field dài bị cắt 300 ký tự ở text mode. Dùng `--json` hoặc `-f markdown --full` để lấy đủ.

## Data
`data/` chứa CSV lõi đã lọc (styles, colors, typography, products, ui-reasoning, landing, charts, ux-guidelines, app-interface, motion, icons, catalog-summary, data-provenance). Đã bỏ gallery/preview/screenshots/i18n để nhẹ.
