#!/bin/sh
set -e

if [ ! -f .env ]; then
    cp .env.example .env
fi

composer install --no-interaction --prefer-dist

php artisan key:generate --force

until mysqladmin ping --ssl=0 -h"${DB_HOST}" -P"${DB_PORT:-3306}" -u"${DB_USERNAME}" -p"${DB_PASSWORD}" --silent; do
    echo "Waiting for MySQL..."
    sleep 2
done

php artisan migrate --seed --force
php artisan config:clear
php artisan route:clear
php artisan view:clear

exec "$@"
