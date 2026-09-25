# AGENT.md — lib/ui-taste

## Mục Đích
Anti-slop frontend cho AI Agent: nâng layout, typography, motion, spacing thay vì sinh UI boilerplate generic. Nguồn chính `Leonxlnx/taste-skill` (89.5k★) + hợp nhất engine offline `nextlevelbuilder/ui-ux-pro-max-skill` (130k★, v2.0). Lấp khoảng trống frontend duy nhất còn thiếu trong monorepo (trước đó chỉ có backend mẫu + memory/compression).

## Kiến Trúc / File Chính
```
lib/ui-taste/
├── AGENT.md        # File này
├── README.md       # Bảng chọn skill + 3 dials + catalog counts + ví dụ lệnh
├── skills/         # 14 SKILL.md (13 taste-skill + 1 router pro-max)
│   ├── design-taste-frontend.md          # v2 experimental — mặc định an toàn nhất
│   ├── design-taste-frontend-v1.md       # v1 ổn định, pin khi v2 thay đổi hành vi
│   ├── gpt-taste.md, image-to-code.md, redesign-existing-projects.md, ...
│   ├── imagegen-frontend-web/mobile.md, brandkit.md
│   └── ui-ux-pro-max.md                  # router: khi nào dùng taste vs pro-max engine
└── engine/         # Offline engine từ ui-ux-pro-max-skill (full catalog, stdlib only)
    ├── search.py, core.py, design_system.py, reasoning_contract.py, validate_data.py
    ├── usage.md    # lệnh đã verify (--design-system, --domain, --stack, --persist)
    └── data/       # CSV lõi: styles, colors, typography, products, ui-reasoning, landing,
                    # charts, ux-guidelines, app-interface, motion, icons + catalog-summary
```
Đã lọc bỏ: `examples/gallery/preview/screenshots/projects/docs/i18n`, `cli/`, `.claude-plugin`, `research/`, `scripts/skill.sh` — chỉ giữ SKILL.md lõi + engine/data (đúng "chỉ tích hợp phần chủ chốt"). Ponytail: không copy thừa.

## Cách Tích Hợp Vào Dự Án
**Trong monorepo:** Agent đọc `skills/<tên>.md` trước khi sinh UI. Nếu cần Design System theo ngành → chạy engine:
```bash
python3 lib/ui-taste/engine/search.py "beauty spa wellness" --design-system -p "Serenity Spa"
python3 lib/ui-taste/engine/search.py "beauty spa wellness" --design-system -p "Serenity Spa" --persist --output-dir "/path/to/project"
python3 lib/ui-taste/engine/search.py "form validation" --stack laravel
```
Xem `engine/usage.md` và `skills/ui-ux-pro-max.md` để chọn taste vs pro-max.

**Khi triển khai dự án ngoài (mục 5 AGENT gốc):**
1. Chọn skill theo bảng trong `README.md` (mặc định `design-taste-frontend` v2).
2. Cách A — CLI chuẩn:
   ```bash
   npx skills add https://github.com/Leonxlnx/taste-skill --skill "design-taste-frontend"
   npx skills add https://github.com/nextlevelbuilder/ui-ux-pro-max-skill --skill "ui-ux-pro-max"  # hoặc uipro init
   ```
   Pin v1 khi cần ổn định: `--skill "design-taste-frontend-v1"`.
3. Cách B — copy thủ công: copy `lib/ui-taste/skills/<tên>.md` hoặc `engine/` vào dự án, hoặc paste vào conversation.
4. Với imagegen: dùng ChatGPT Images / Codex image mode để sinh comps (`imagegen-frontend-web/mobile`, `brandkit`), rồi đưa renders cho coding agent + `image-to-code` skill.

Skill markdown không cần dependency; engine chỉ cần Python 3.x stdlib.

## Dependencies / Yêu Cầu
- Text-mode skills: không cần cài — agent chỉ đọc file.
- Imagegen: cần ChatGPT Images / Codex image mode.
- Engine: Python 3.x (stdlib only, không cài thêm pip), có `uipro` CLI nếu muốn cài global (`npm i -g ui-ux-pro-max-cli`).
- Tương thích: React/Vue/Svelte/Laravel(Blade/Livewire/Inertia)... 22 stacks, framework-agnostic.

## Lưu Ý / Bài Học
- v2 hiện là `experimental`. Cần ổn định → pin `design-taste-frontend-v1`.
- 3 dials taste: `DESIGN_VARIANCE` (centered→asymmetric), `MOTION_INTENSITY` (hover→scroll/magnetic), `VISUAL_DENSITY` (spacious→dense).
- Engine: output text truncate 300 ký tự → dùng `--json --full` hoặc `-f markdown` để lấy đủ; stack search version-aware (legacy rỗng nếu không match); persist tạo `design-system/<slug>/MASTER.md` + `pages/<page>.md` (hierarchical, `--force` mới ghi đè).
- Ponytail: chọn 1 skill mặc định trước (YAGNI), chỉ thêm khi brief yêu cầu rõ. Không cài cả 14 vào 1 dự án. Ưu tiên engine khi cần reasoning theo ngành (192 rules + 192 palettes), dùng taste khi cần gu nhanh.

---
*Cập nhật: 2026-09-23 — Hợp nhất ui-ux-pro-max-skill engine offline (79 styles/74 fonts/119 UX) vào ui-taste (trước đó 13 SKILL.md taste-skill) + router ui-ux-pro-max.md*
