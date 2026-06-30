#!/bin/bash
set -e

echo "==> Starting Jem Designs..."

# Ensure storage directories exist
mkdir -p /var/www/storage/app/public \
    /var/www/storage/framework/cache/data \
    /var/www/storage/framework/sessions \
    /var/www/storage/framework/views \
    /var/www/storage/logs \
    /var/www/bootstrap/cache

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Storage symlink
php artisan storage:link --force 2>/dev/null || true

# Wait for MariaDB using raw TCP (doesn't require APP_KEY)
echo "==> Waiting for database..."
for i in $(seq 1 30); do
    if mysqladmin ping -h"${DB_HOST:-db}" -u"${DB_USERNAME:-laravel}" -p"${DB_PASSWORD:-laravel}" --silent 2>/dev/null; then
        echo "==> Database ready!"
        break
    fi
    echo "    Attempt $i/30..."
    sleep 2
done

# Run migrations
echo "==> Running migrations..."
php artisan migrate --force 2>&1 || echo "==> Migration warning (may already be up to date)"

# Seed only if users table is empty
HAS_USERS=$(mysql -h"${DB_HOST:-db}" -u"${DB_USERNAME:-laravel}" -p"${DB_PASSWORD:-laravel}" "${DB_DATABASE:-laravel}" -sse "SELECT COUNT(*) FROM users;" 2>/dev/null || echo "0")
if [ "$HAS_USERS" = "0" ]; then
    echo "==> Seeding database..."
    php artisan db:seed --force 2>&1 || echo "==> Seed warning (non-fatal)"
else
    echo "==> Database already seeded ($HAS_USERS users)"
fi

# Warm caches
php artisan config:cache 2>/dev/null || true
php artisan route:cache  2>/dev/null || true
php artisan view:cache   2>/dev/null || true

# Fix storage permissions one more time
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

echo "==> Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
