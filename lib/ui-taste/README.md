# lib/ui-taste — Anti-Slop Frontend Skills + Offline Engine

Cho AI gu thẩm mỹ (taste-skill) + Design System có kiểm chứng theo ngành (pro-max engine offline).

## Cài nhanh (dự án ngoài)
```bash
# Mặc định an toàn nhất (v2 experimental)
npx skills add https://github.com/Leonxlnx/taste-skill --skill "design-taste-frontend"

# Pin v1 khi cần ổn định
npx skills add https://github.com/Leonxlnx/taste-skill --skill "design-taste-frontend-v1"

# Thêm skill khác trong full bộ
npx skills add https://github.com/Leonxlnx/taste-skill --skill "minimalist-ui"
```

Hoặc copy `lib/ui-taste/skills/<tên>.md` vào dự án / paste vào conversation.

## Engine offline (pro-max, đã hợp nhất — stdlib only, Python 3.x)
```bash
python3 lib/ui-taste/engine/search.py "beauty spa wellness" --design-system -p "Serenity Spa"
python3 lib/ui-taste/engine/search.py "beauty spa wellness" --design-system -p "Serenity Spa" -f markdown --json
python3 lib/ui-taste/engine/search.py "SaaS dashboard" --design-system -p "MyApp" --persist --output-dir "/path/to/project"
python3 lib/ui-taste/engine/search.py "glassmorphism" --domain style --json
python3 lib/ui-taste/engine/search.py "form validation" --stack laravel --json
```
Chi tiết: `engine/usage.md` (đã verify), `skills/ui-ux-pro-max.md` (router taste vs pro-max).

## Catalog (full offline, đã verify)
79 searchable styles (50 active) · 192 palettes · 74 font pairings · 34 landing patterns · 192 product types · 25 chart types · 22 stacks (React/Vue/Laravel Blade/Livewire/….) · 119 UX guidelines · BM25 search.

## Bảng chọn skill (14 = 13 taste + 1 router pro-max)

| Skill (file) | Install name | Khi nào dùng |
|---|---|---|
| `design-taste-frontend.md` | `design-taste-frontend` | 🆕 v2 mặc định — brief inference, 3 dials, GSAP skeletons, redesign-audit |
| `design-taste-frontend-v1.md` | `design-taste-frontend-v1` | Cần hành vi y hệt bản gốc, không muốn v2 experimental |
| `gpt-taste.md` | `gpt-taste` | Dùng GPT/Codex — stricter layout variance + motion |
| `image-to-code.md` | `image-to-code` | Pipeline ảnh → phân tích → code (nói rõ trong prompt) |
| `redesign-existing-projects.md` | `redesign-existing-projects` | Cải tạo UI codebase có sẵn (audit trước, sửa sau) |
| `high-end-visual-design.md` | `high-end-visual-design` | Calm, expensive, whitespace, premium fonts |
| `full-output-enforcement.md` | `full-output-enforcement` | Agent hay cắt xén placeholder |
| `minimalist-ui.md` | `minimalist-ui` | Editorial Notion/Linear, palette tiết chế |
| `industrial-brutalist-ui.md` | `industrial-brutalist-ui` | Swiss type, contrast mạnh, layout thử nghiệm |
| `stitch-design-taste.md` | `stitch-design-taste` | Google Stitch + export DESIGN.md |
| `imagegen-frontend-web.md` | `imagegen-frontend-web` | Sinh comp web (hero/landing) — chỉ ra ảnh |
| `imagegen-frontend-mobile.md` | `imagegen-frontend-mobile` | Sinh comp mobile (iOS/Android flows) — chỉ ra ảnh |
| `brandkit.md` | `brandkit` | Logo/palette/type/identity boards — chỉ ra ảnh |

> Imagegen skills chỉ ra ảnh, không ra code. Dùng ChatGPT Images / Codex image mode rồi đưa renders cho coding agent.

| `ui-ux-pro-max.md` | *(router)* | Khi nào dùng taste vs pro-max engine (192 rules + persist MASTER.md) |

## 3 Dials (đầu file `design-taste-frontend.md`)
| Dial | 1 (thấp) | 10 (cao) |
|---|---|---|
| `DESIGN_VARIANCE` | Centered, clean | Asymmetric, modern |
| `MOTION_INTENSITY` | Hover nhẹ | Scroll + magnetic |
| `VISUAL_DENSITY` | Spacious | Dense dashboard |

## Quy tắc Ponytail
Chọn 1 skill mặc định trước (thường `design-taste-frontend`), chỉ thêm skill khác khi brief yêu cầu rõ hướng visual.

---
*Nguồn: Leonxlnx/taste-skill (13 SKILL.md) + nextlevelbuilder/ui-ux-pro-max-skill engine offline (79 styles/192 palettes/74 fonts, đã fetch & verify 2026-09-23). Lọc bỏ examples/gallery/preview/cli/i18n.*

