# Starts the stack with the app on host networking (Linux / WSL2 Linux engine; see docker-compose.host-network.yml).
# From project directory:
#   .\scripts\start-host-network.ps1

$ErrorActionPreference = "Stop"
Set-Location (Split-Path -Parent $PSScriptRoot)

docker compose -f docker-compose.host-network.yml up -d --build

Write-Host "`nOpen: http://127.0.0.1:<APP_PORT from .env>/ (e.g. 8081)" -ForegroundColor Green
