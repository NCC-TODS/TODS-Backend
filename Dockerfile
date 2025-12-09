# syntax=docker/dockerfile:1.6

ARG PHP_VERSION=8.3
ARG BUILD_ENV=production

## Base PHP image with required extensions
FROM php:${PHP_VERSION}-fpm-alpine AS base
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_NO_INTERACTION=1

RUN apk add --no-cache \
        bash \
        curl \
        git \
        icu-dev \
        freetype-dev \
        libjpeg-turbo-dev \
        libpng-dev \
        libzip-dev \
        mariadb-client \
        oniguruma-dev \
        $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        gd \
        intl \
        opcache \
        pdo_mysql \
        zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del --no-network $PHPIZE_DEPS

WORKDIR /var/www/html

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

## Composer dependencies (dev/prod controlled by BUILD_ENV)
FROM base AS vendor
ARG BUILD_ENV=production
WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN if [ "$BUILD_ENV" = "production" ]; then \
        composer install --no-dev --optimize-autoloader --no-scripts --no-progress; \
    else \
        composer install --optimize-autoloader --no-scripts --no-progress; \
    fi

## Frontend assets build
FROM node:20-alpine AS assets
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources ./resources
COPY vite.config.js ./

RUN npm run build

## Runtime image
FROM base AS runtime
ARG BUILD_ENV=production
ENV BUILD_ENV=${BUILD_ENV}
WORKDIR /var/www/html

COPY . .

# Vendor and built assets from previous stages
COPY --from=vendor /var/www/html/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

RUN mkdir -p storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Configure opcache for prod, keep disabled for dev
RUN if [ "$BUILD_ENV" = "production" ]; then \
        { \
            echo 'opcache.enable=1'; \
            echo 'opcache.enable_cli=0'; \
            echo 'opcache.memory_consumption=128'; \
            echo 'opcache.max_accelerated_files=10000'; \
            echo 'opcache.validate_timestamps=0'; \
        } > /usr/local/etc/php/conf.d/opcache.ini; \
    else \
        { echo 'opcache.enable=0'; } > /usr/local/etc/php/conf.d/opcache.ini; \
    fi

CMD ["php-fpm"]

