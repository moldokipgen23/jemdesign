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

# Wait for MariaDB (max 60s)
echo "==> Waiting for database..."
for i in $(seq 1 20); do
    if php artisan db:show > /dev/null 2>&1; then
        echo "==> Database connected!"
        break
    fi
    echo "    Attempt $i/20..."
    sleep 3
done

# Run migrations
echo "==> Running migrations..."
php artisan migrate --force 2>&1 || echo "==> Migration warning (may already be up to date)"

# Seed only if users table is empty
USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null || echo "0")
if [ "$USER_COUNT" = "0" ]; then
    echo "==> Seeding database..."
    php artisan db:seed --force 2>&1 || echo "==> Seed warning (non-fatal)"
else
    echo "==> Database already seeded ($USER_COUNT users)"
fi

# Fix storage permissions one more time
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

echo "==> Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
