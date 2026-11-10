#!/usr/bin/env bash
set -e

echo "==> [SIPAS] Preparing Laravel application for Railway..."

# 1. Pastikan folder storage dan bootstrap cache tersedia
mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache
php artisan storage:link || true

# 2. Cache konfigurasi, rute, dan view untuk performa production
echo "==> [SIPAS] Caching configuration, routes, and views..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Jalankan migrasi database secara otomatis
echo "==> [SIPAS] Running database migrations..."
php artisan migrate --force

# 4. Jalankan web server
echo "==> [SIPAS] Starting web server on port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"

