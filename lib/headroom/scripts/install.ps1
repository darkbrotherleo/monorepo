# install.ps1 — Cài Headroom (local-first context compression)
# ponytail: one-liner cài đặt, không boilerplate thừa
param([string]$Python = "python")

Write-Host "[headroom] Cài headroom-ai[all] ..." -ForegroundColor Cyan
& $Python -m pip install --upgrade "headroom-ai[all]"
if ($LASTEXITCODE -ne 0) { Write-Error "Cài thất bại. Thử: pip install headroom-ai[all] hoặc uv tool install --python 3.13 headroom-ai[all]"; exit 1 }
headroom --version
Write-Host "[headroom] Xong. Chạy .\wrap.ps1 để khởi proxy." -ForegroundColor Green
