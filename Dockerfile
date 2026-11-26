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

CMD ["sh", "-c", "if [ ! -f .env ]; then cp .env.example .env && sed -i 's/^DB_/#DB_/g; s/^SESSION_/#SESSION_/g; s/^CACHE_/#CACHE_/g' .env && php artisan key:generate --force --no-interaction; fi; mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache/data storage/logs bootstrap/cache; php artisan config:clear 2>/dev/null || true; php artisan migrate --force --no-interaction || true; php artisan serve --host=0.0.0.0 --port=8000"]
