#!/bin/sh
set -e
# Bridge mode: PHP_LISTEN_PORT=8080 and Compose maps host APP_PORT -> 8080.
# Host network mode: set PHP_LISTEN_PORT to APP_PORT so PHP binds on the host port directly.
PORT="${PHP_LISTEN_PORT:-8080}"
exec php -S "0.0.0.0:${PORT}" -t /var/www/html
