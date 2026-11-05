#!/bin/sh
# Arranque del contenedor: prepara la base de datos y levanta Apache.
set -e
cd /var/www/html

# Render y Railway indican el puerto en la variable PORT.
PORT="${PORT:-80}"
sed -ri "s/Listen [0-9]+/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Con SQLite, el archivo de base de datos se crea si no existe.
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    touch database/database.sqlite
    chown www-data:www-data database database/database.sqlite
fi

php artisan migrate --force

# Datos de demostración solo si la base está vacía.
if [ "$(php artisan db:table-count 2>/dev/null || echo 0)" = "0" ]; then
    php artisan db:seed --force
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache
chown -R www-data:www-data storage bootstrap/cache

exec apache2-foreground
