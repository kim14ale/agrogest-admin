# Imagen para desplegar AgroGest Admin (Render, Railway o cualquier host con Docker)
FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libzip-dev libicu-dev libsqlite3-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite zip intl \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Apache sirve la carpeta public/ de Laravel
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && sed -ri 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress \
    && cp -n .env.example .env \
    && php artisan key:generate --force \
    && chown -R www-data:www-data storage bootstrap/cache database

CMD ["sh", "docker/start.sh"]
