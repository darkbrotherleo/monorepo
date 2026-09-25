# call.ps1 — Call external openbrowser sidecar from PHP/Laragon
param([string]$Task = "extract pricing from https://example.com", [string]$Provider = "openai")

Write-Host "[web-automation] Run sidecar: $Task (provider $Provider)" -ForegroundColor Cyan
# Option A: call external service if running
# curl http://127.0.0.1:3000/run -X POST -d "{`"task`":`"$Task`"}"
# Option B: npx direct (needs Bun)
npx --yes open-browser run "$Task" -p $Provider --json | Out-File -FilePath "$PSScriptRoot\..\result.json"
Write-Host "[web-automation] Result -> result.json" -ForegroundColor Green
Get-Content "$PSScriptRoot\..\result.json" | Select-Object -First 20
