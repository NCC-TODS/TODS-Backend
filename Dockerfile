FROM php:8.2-fpm-alpine

# نصب وابستگی‌های لازم و اکستنشن‌های PHP
RUN apk update && apk add --no-cache \
    $PHPIZE_DEPS \
    build-base \
    libzip-dev \
    libpng-dev \
    icu-dev \
    git \
    openssl \
    curl \
    mariadb-client \
    && docker-php-ext-install pdo_mysql opcache zip bcmath gd \
    && docker-php-ext-enable opcache

# نصب Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# تنظیم دایرکتوری کاری به /var/www/html (که دایرکتوری پیش‌فرض Nginx/وب‌سرور خواهد بود)
WORKDIR /var/www/html

# کپی کردن کدهای پروژه به داخل کانتینر
COPY . .

# تنظیم دسترسی‌ها
RUN chown -R www-data:www-data /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html/bootstrap/cache

# در نهایت، اجرای PHP-FPM
CMD ["php-fpm"]