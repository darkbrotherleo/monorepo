# AGENT.md — lib/security-audit

## Mục Đích
Audit bảo mật cho code vibe-coded (AI sinh nhanh, dễ sót): chặn secrets lộ, auth yếu, SQLi, mass-assignment, price tampering, rate-limit thiếu, deployment hở. Nguồn: `raroque/vibe-security-skill` (MIT, 1k★, `vibe-security/SKILL.md`). Đã lọc bỏ Supabase RLS/Firebase (database-security) + Mobile (Expo/SecureStore) không phù hợp Laragon MySQL/PHP thuần.

## Kiến Trúc / File Chính
```
lib/security-audit/
├── AGENT.md        # File này
├── SKILL.md        # Skill gốc đã fetch (7041B, Audits via never-trust-client)
├── README.md       # Bảng severity + cách chạy audit cho Slim
└── references/     # 7 files đã fetch + sẽ adapt cho Slim (MIT)
    ├── secrets-and-env.md      # gitleaks, .env.example, sk_live_/AKIA detect
    ├── authentication.md       # jwt.verify + middleware + Server Action → Slim handler
    ├── rate-limiting.md        # per-IP + per-user, Redis/Upstash pattern
    ├── payments.md             # server-side Price ID, Stripe webhook raw body
    ├── data-access.md          # PDO prepare + Mass assignment whitelist (adapt từ Prisma/Zod)
    ├── deployment.md           # headers CSP/HSTS, .git/sourceMaps expose, CORS *
    └── ai-integration.md       # proxy AI keys, billing caps, prompt injection
```
Nguyên bản Supabase/Firebase + mobile đã loại (không copy). Giữ output format của skill: `file:line` + exploit impact + before/after fix.

## Cách Tích Hợp Vào Dự Án
**Trong monorepo:** Agent đọc `SKILL.md` + `references/<domain>.md` trước khi audit, trả kết quả theo severity Critical→Low.

**Khi triển khai dự án ngoài (PHP Slim / Laragon):**
1. Copy `lib/security-audit/references/*.md` + `SKILL.md` vào dự án hoặc chạy trực tiếp từ monorepo.
2. Audit checklist (dịch cho Slim):
   - **Secrets:** `gitleaks detect --source .`, check `.gitignore` có `.env`, quét `sk_live_`, `AKIA`, `ghp_` trong `public/js` bundle.
   - **Auth:** `firebase/php-jwt` phải `JWT::decode(..., verify=true)`, reject `alg:none`, check `iss/aud/exp`; Slim middleware chỉ là guard đầu — controller/service phải re-check `user_id`/`role`.
   - **Data Access (adapt quan trọng):** PDO `prepare('SELECT ... WHERE id = ?')` + `execute([$id])`; không `query("... WHERE id = '$id'")`; whitelist field `['name','email']` thay vì `where: req.body`; thay Zod bằng `respect/validation` hoặc `Valitron`.
   - **Rate Limit:** middleware `slim-rate-limit` + Redis (per-IP + per-user + billing caps).
   - **Payments:** không tin `unit_amount` từ client — lookup Price ID server-side; webhook dùng `file_get_contents('php://input')` + `Stripe\Webhook::constructEvent()` với raw body.
   - **Deployment:** `Header set Content-Security-Policy` / `X-Frame-Options` / `HSTS` trong Apache `.htaccess`/Nginx; cấm expose `.git`, `sourceMaps`, debug.
3. Lệnh CLI nếu cài skill: `npx skills add https://github.com/raroque/vibe-security-skill --skill vibe-security` (hoặc copy thủ công).

## Dependencies / Yêu Cầu
- Không bắt buộc cài — skill chạy bằng đọc file + grep. Nếu muốn guard runtime: `firebase/php-jwt`, `respect/validation`, `monolog`, Redis cho rate-limit, `gitleaks` cho CI.
- PHP >=8.2, Slim 4, `lib/php-stack` đã có JWT/validation/log.

## Lưu Ý / Bài Học
- Core principle: **Never trust the client** — mọi price/userId/role/subscription phải validate server-side.
- Slim middleware CVE-2025-29927 (Next.js) → analog: đừng coi middleware là sole auth, controller phải verify lại.
- Detect nhanh PHP smell: `grep -R "query.*\$_"`, `grep -R "where.*\$_POST"`, `grep -R "sk_live" public/`.
- Ponytail: chọn 2-3 domain audit trước (Secrets + Data Access + Auth) thay vì audit cả 7 cùng lúc; chỉ thêm Payments/AI khi dự án có.

---
*Cập nhật: 2026-09-23 — Tích hợp raroque/vibe-security-skill (fetch 7/9 refs, lọc bỏ database-security/mobile, adapt cho Slim/PDO/Laragon)*
