# Run from project root. Checks ICMP from inside the app container (same as the PHP ping pages).
param(
    [string] $ContainerName = "itp4416_app",
    [string] $Target = "8.8.8.8"
)

Write-Host "Container: $ContainerName  Target: $Target`n" -ForegroundColor Cyan

Write-Host "--- docker exec $ContainerName ping -c 4 $Target ---" -ForegroundColor Yellow
docker exec $ContainerName ping -c 4 $Target 2>&1

Write-Host "`n--- docker inspect (NetworkMode / CapAdd) ---" -ForegroundColor Yellow
docker inspect $ContainerName --format '{{json .HostConfig.NetworkMode}} {{json .HostConfig.CapAdd}}' 2>&1
