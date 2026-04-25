# Base image tag should match PHP_IMAGE in .env (see .env.example); bump for security updates.
ARG PHP_IMAGE=php:8.2-cli-bookworm
FROM ${PHP_IMAGE}

RUN docker-php-ext-install mysqli \
    && apt-get update \
    && apt-get install -y --no-install-recommends iputils-ping \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
COPY . .

EXPOSE 8080

# PHP built-in web server (no Apache). Document root = project root (index.php, secure/, vulnerable/).
# Execute via `sh` to avoid CRLF/shebang issues on Windows checkouts.
ENTRYPOINT ["sh", "/usr/local/bin/docker-entrypoint.sh"]
