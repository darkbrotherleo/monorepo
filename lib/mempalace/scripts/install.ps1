# install.ps1 — Cài MemPalace (local-first verbatim memory)
param([string]$ProjectDir = "$HOME\projects\myapp")

Write-Host "[mempalace] Cài mempalace ..." -ForegroundColor Cyan
if (Get-Command uv -ErrorAction SilentlyContinue) {
  uv tool install mempalace
} elseif (Get-Command pipx -ErrorAction SilentlyContinue) {
  pipx install mempalace
} else {
  python -m pip install mempalace
}
if ($LASTEXITCODE -ne 0) { Write-Error "Cài thất bại. Cài uv: https://docs.astral.sh/uv/ rồi thử lại."; exit 1 }

Write-Host "[mempalace] Init palace tại $ProjectDir ..." -ForegroundColor Cyan
mempalace init $ProjectDir
Write-Host "[mempalace] Xong. Mine: mempalace mine $ProjectDir | search: mempalace search 'query' | wake-up: mempalace wake-up" -ForegroundColor Green
Write-Host "[mempalace] Docs: https://mempalaceofficial.com/guide/getting-started" -ForegroundColor DarkGray
