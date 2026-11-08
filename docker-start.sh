#!/bin/sh
set -e
cd /var/www/html
php artisan package:discover --ansi || true
php artisan migrate --force --seed
php artisan storage:link --force || true
exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}" --no-reload
