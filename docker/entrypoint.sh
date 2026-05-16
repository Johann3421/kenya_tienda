#!/usr/bin/env sh
set -eu

cd /var/www/html

mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache

# Si el volumen está vacío o le faltan imágenes (porque Docker Compose lo ocultó), copiarlas de vuelta
if [ -d "/var/www/initial_storage_public" ]; then
    echo "Sincronizando imágenes y archivos predeterminados al volumen persistente..."
    cp -rn /var/www/initial_storage_public/* storage/app/public/ || true
fi

chown -R www-data:www-data storage bootstrap/cache || true

php artisan storage:link || true

if [ "${APP_ENV:-production}" = "production" ]; then
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    php artisan migrate --force
fi

exec "$@"
