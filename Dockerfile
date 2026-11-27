FROM php:8.2-cli-alpine

# Dependências de sistema e extensões PHP para Laravel + MySQL/MariaDB
RUN apk add --no-cache \
    bash \
    curl \
    git \
    unzip \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    mysql-client \
  && docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    zip \
    intl \
    bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .
RUN composer dump-autoload --optimize \
  && mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache/data storage/logs bootstrap/cache \
  && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD ["sh", "-c", "if [ ! -f .env ]; then cp .env.example .env && php artisan key:generate --force --no-interaction; fi; sed -i 's/^APP_URL=/#APP_URL=/g; s/^DB_/#DB_/g; s/^SESSION_/#SESSION_/g; s/^CACHE_/#CACHE_/g; s/^ADMIN_/#ADMIN_/g' .env && printf '\nAPP_URL=%s\nDB_CONNECTION=%s\nDB_HOST=%s\nDB_PORT=%s\nDB_DATABASE=%s\nDB_USERNAME=%s\nDB_PASSWORD=%s\nSESSION_DRIVER=%s\nCACHE_STORE=%s\nGATEWAY_URL=%s\nSSO_SECRET=%s\nADMIN_NAME=\"%s\"\nADMIN_EMAIL=%s\nADMIN_USER=%s\nADMIN_PASSWORD=%s\n' \"${APP_URL:-http://localhost:8000}\" \"${DB_CONNECTION:-mysql}\" \"${DB_HOST:-ets-db}\" \"${DB_PORT:-3306}\" \"${DB_DATABASE:-sistema_ets}\" \"${DB_USERNAME:-sistema_ets}\" \"${DB_PASSWORD:-etspassword}\" \"${SESSION_DRIVER:-file}\" \"${CACHE_STORE:-file}\" \"${GATEWAY_URL:-http://localhost:4000}\" \"${SSO_SECRET}\" \"${ADMIN_NAME:-Administrador do Sistema}\" \"${ADMIN_EMAIL:-admin@spd.local}\" \"${ADMIN_USER:-admin}\" \"${ADMIN_PASSWORD:-admin123}\" >> .env; mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache/data storage/logs bootstrap/cache; php artisan config:clear 2>/dev/null || true; php artisan migrate --force --no-interaction || true; php artisan serve --host=0.0.0.0 --port=8000"]
