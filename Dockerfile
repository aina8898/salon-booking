FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN cp .env.example .env

RUN touch database/database.sqlite
RUN php artisan migrate --force
RUN php artisan db:seed --force
RUN php artisan optimize

RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

EXPOSE 80