# ui-ux-pro-max — Router giữa taste-skill (gu nhanh) và pro-max engine (catalog offline)

> Quyết định khi nào dùng taste dials vs pro-max reasoning. Ponytail: chọn 1 trước, chỉ thêm khi brief yêu cầu rõ.

## Khi nào dùng taste-skill (13 SKILL.md có sẵn)
- Cần thẩm mỹ nhanh, layout/typography/motion/spacing theo 3 dials (VARIANCE/MOTION/DENSITY).
- Dự án greenfield, brief ngắn, không cần reasoning theo ngành.
- Dùng: `design-taste-frontend` v2 (mặc định) hoặc `minimalist-ui`/`industrial-brutalist-ui`/`high-end-visual-design` khi đã chốt visual direction.

## Khi nào dùng pro-max engine (offline, 79 styles/74 fonts/119 UX)
- Cần Design System có kiểm chứng theo ngành (192 product types + 192 palettes + 74 font pairings + 34 landing patterns).
- Cần sinh ra spec đầy đủ: Pattern + Style + Colors + Typography + Effects + Anti-patterns + Pre-delivery checklist.
- Cần stack guideline chính xác (Laravel Blade/Livewire/Inertia, React, Vue, html-tailwind, ... 22 stacks).
- Muốn persist MASTER.md + pages/ overrides để hierarchical retrieval.

## Lệnh engine (chạy tại root monorepo, stdlib only, Python 3.x)
```bash
python3 lib/ui-taste/engine/search.py "beauty spa wellness" --design-system -p "Serenity Spa"
python3 lib/ui-taste/engine/search.py "beauty spa wellness" --design-system -p "Serenity Spa" -f markdown
python3 lib/ui-taste/engine/search.py "glassmorphism" --domain style --json --full
python3 lib/ui-taste/engine/search.py "SaaS dashboard" --design-system --persist -p "MyApp" --output-dir "/path/to/project" --page "dashboard"
python3 lib/ui-taste/engine/search.py "form validation" --stack laravel
python3 lib/ui-taste/engine/search.py "form validation" --stack html-tailwind
```

## Lưu ý
- Output text truncate 300 ký tự cho field dài — dùng `--json --full` hoặc `-f markdown` để lấy đủ.
- Stack search version-aware: không chỉ định version cũ thì trả active/current; legacy chỉ khi ghi rõ (vd. Svelte 4) và có thể trả rỗng (đúng, không trộn generation).
- Persist: `design-system/<slug>/MASTER.md` là source of truth; `pages/<page>.md` override; `--force` mới ghi đè.
- Imagegen (taste) chỉ ra ảnh → đưa renders cho engine/code agent để implement (pipeline image-to-code).
