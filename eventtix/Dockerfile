FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev libpq-dev zip unzip nginx \
    && docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# Ensure required directories exist and are writable BEFORE composer runs
RUN mkdir -p bootstrap/cache storage/framework/sessions storage/framework/views storage/framework/cache \
    && chown -R www-data:www-data bootstrap/cache storage

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

EXPOSE 9000
CMD php artisan config:clear; php artisan migrate --force; php artisan serve --host=0.0.0.0 --port=$PORT