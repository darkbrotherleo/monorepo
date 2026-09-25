# lib/security-audit — Audit cho vibe-coded PHP (Slim)

Bắt các lỗi AI hay sinh sót: secrets lộ, auth yếu, SQLi/mass-assignment, price tampering, rate-limit thiếu.

## Chạy audit (Slim / Laragon)
```bash
# 1. Quét secrets (gitleaks)
gitleaks detect --source .

# 2. Grep smell PHP (adapt từ skill)
grep -R "query.*\$_" src/
grep -R "where.*\$_POST" src/
grep -R "sk_live\|AKIA\|ghp_" public/

# 3. Đọc checklist theo domain
cat lib/security-audit/references/secrets-and-env.md
cat lib/security-audit/references/data-access.md      # PDO + whitelist
cat lib/security-audit/references/authentication.md   # jwt.verify
```

Hoặc cài skill gốc: `npx skills add https://github.com/raroque/vibe-security-skill --skill vibe-security`

## 7 domains (đã lọc — bỏ database-security/mobile)

| Domain | File | Slim adapt |
|---|---|---|
| Secrets | `references/secrets-and-env.md` | `.env` + `.gitignore`, quét `VITE_/NEXT_PUBLIC_` → check `public/js` bundle |
| Authentication | `references/authentication.md` | `firebase/php-jwt` verify + reject `alg:none`, re-check role trong controller |
| Rate Limiting | `references/rate-limiting.md` | `slim-rate-limit` + Redis per-IP/per-user + billing caps |
| Payments | `references/payments.md` | Lookup Price ID server-side, webhook `php://input` + `Stripe\Webhook::constructEvent` |
| Data Access | `references/data-access.md` | PDO `prepare`/`execute`, `respect/validation`, whitelist `['name','email']` (không spread `$_POST`) |
| Deployment | `references/deployment.md` | CSP/HSTS/X-Frame trong `.htaccess`/Nginx, cấm `.git`/`sourceMaps` |
| AI Integration | `references/ai-integration.md` | Proxy key server-side, per-user quota |

## Output format (từ SKILL.md)
Severity `Critical → High → Medium → Low`, mỗi finding gồm `file:line` + exploit impact + before/after fix.

## Khi nào audit
Mọi khi viết/review code auth/payments/DB/API keys — ngay cả khi user không nói "security". Ponytail: audit 2-3 domain cốt lõi trước (Secrets/Data-Access/Auth), thêm domain khác khi dự án có.

---
*Nguồn: raroque/vibe-security-skill MIT — fetch 7/9 refs 2026-09-23, đã adapt cho PHP thuần/Slim.*
