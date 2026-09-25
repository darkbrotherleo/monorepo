# wrap.ps1 — Khởi proxy Headroom + wrap OpenCode
# ponytail: tối giản — chỉ proxy và wrap, không logic thừa
param([int]$Port = 8787, [string]$Agent = "opencode")

Write-Host "[headroom] Khởi proxy port $Port ..." -ForegroundColor Cyan
Start-Process -NoNewWindow headroom -ArgumentList "proxy --port $Port" -PassThru | Out-Null
Start-Sleep -Seconds 2
headroom doctor
Write-Host "[headroom] Wrap $Agent ..." -ForegroundColor Cyan
headroom wrap $Agent
Write-Host "[headroom] Đang chạy. Dashboard: headroom dashboard | Logs: headroom perf" -ForegroundColor Green
