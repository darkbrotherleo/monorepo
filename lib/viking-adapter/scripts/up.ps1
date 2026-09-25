# up.ps1 — Run external OpenViking server (AGPL stays outside monorepo)
param([string]$DataDir = "$PSScriptRoot\..\..\..\viking-data")

Write-Host "[viking] Pull & up ghcr.io/volcengine/openviking:latest (data: $DataDir)" -ForegroundColor Cyan
New-Item -ItemType Directory -Path $DataDir -Force | Out-Null
docker pull ghcr.io/volcengine/openviking:latest
docker compose -f "$PSScriptRoot\docker-compose.yml" up -d
Write-Host "[viking] API http://127.0.0.1:1933  Studio http://127.0.0.1:1934" -ForegroundColor Green
