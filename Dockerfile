# Dexter Auto Services — production image for Render.
# One container runs: nginx (website), php-fpm (PHP), the queue worker (emails + phone alerts)
# and the scheduler (daily reminders, archiving).

# ── 1. Build the CSS/JS with Vite ─────────────────────────────────────────
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# ── 2. Install PHP packages (production only) ─────────────────────────────
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --no-interaction --prefer-dist --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative --no-scripts

# ── 3. Runtime ────────────────────────────────────────────────────────────
FROM php:8.4-fpm-alpine

RUN apk add --no-cache nginx supervisor libpq libpng libjpeg-turbo freetype libzip icu-libs gmp \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS postgresql-dev libpng-dev libjpeg-turbo-dev \
        freetype-dev libzip-dev icu-dev gmp-dev linux-headers \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_pgsql pgsql gd zip intl bcmath gmp opcache pcntl exif \
    && apk del .build-deps

COPY docker/php.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/zz-app.conf
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/start.sh /usr/local/bin/start
# Strip Windows line endings (the project is edited on Windows)
RUN sed -i 's/\r$//' /usr/local/bin/start /etc/supervisord.conf /etc/nginx/http.d/default.conf \
    && chmod +x /usr/local/bin/start

WORKDIR /var/www/html
COPY --chown=www-data:www-data . .
COPY --chown=www-data:www-data --from=vendor /app/vendor ./vendor
COPY --chown=www-data:www-data --from=assets /app/public/build ./public/build

RUN rm -f public/hot .env \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Render sends traffic to $PORT (10000 by default)
ENV PORT=10000
EXPOSE 10000

CMD ["start"]
