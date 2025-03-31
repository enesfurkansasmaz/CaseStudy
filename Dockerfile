FROM php:8.2-fpm

RUN apt-get update && apt-get install -y     libpng-dev libjpeg-dev libfreetype6-dev zip git curl libzip-dev     && docker-php-ext-configure gd --with-freetype --with-jpeg     && docker-php-ext-install gd pdo pdo_mysql zip     && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY . .

# Composer yüklemesi daha hızlı ve temiz haliyle
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html     && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]