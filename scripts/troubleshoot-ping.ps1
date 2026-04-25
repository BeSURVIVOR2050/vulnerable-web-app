# Run from anywhere: resolves project root and uses service name `app` (no fixed container_name).
param(
    [string] $Target = "8.8.8.8"
)

$ErrorActionPreference = "Stop"
$ProjectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $ProjectRoot

Write-Host "Project: $ProjectRoot  Service: app  Target: $Target`n" -ForegroundColor Cyan

Write-Host "--- docker compose exec app ping -c 4 $Target ---" -ForegroundColor Yellow
docker compose exec app ping -c 4 $Target 2>&1

$cid = docker compose ps -q app
if (-not $cid) {
    Write-Host "`nApp container not running or compose project mismatch. Start with: docker compose up -d" -ForegroundColor Red
    exit 1
}

Write-Host "`n--- docker inspect (NetworkMode / CapAdd) ---" -ForegroundColor Yellow
docker inspect $cid.Trim() --format '{{json .HostConfig.NetworkMode}} {{json .HostConfig.CapAdd}}' 2>&1
