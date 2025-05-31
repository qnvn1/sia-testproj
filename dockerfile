FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip zip libonig-dev libzip-dev curl \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

ENV PORT=10000
EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=$PORT
