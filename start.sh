#!/usr/bin/env bash
set -e

echo "==> [SIPAS] Preparing Laravel application for Railway..."

# 1. Pastikan folder storage dan bootstrap cache tersedia
mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache
rm -rf public/storage
php artisan storage:link || true

# 2. Pastikan SESSION_DRIVER=database di production (file session tidak persisten di Railway)
if [ -z "$SESSION_DRIVER" ] || [ "$SESSION_DRIVER" = "file" ]; then
    export SESSION_DRIVER=database
fi

# 3. Clear semua cache lama, lalu build ulang cache production
echo "==> [SIPAS] Clearing old cache and rebuilding for production..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 3. Jalankan migrasi database secara otomatis
echo "==> [SIPAS] Running database migrations..."
php artisan migrate --force

# 4. Jalankan web server
echo "==> [SIPAS] Starting web server on port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"

