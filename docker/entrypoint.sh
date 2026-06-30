#!/bin/bash
set -e

echo "Starting Jem Designs & Co..."

# Ensure storage directories exist
mkdir -p /var/www/storage/app/public
mkdir -p /var/www/storage/framework/cache
mkdir -p /var/www/storage/framework/sessions
mkdir -p /var/www/storage/framework/views
mkdir -p /var/www/storage/logs
mkdir -p /var/www/bootstrap/cache

# Fix permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Create storage symlink if it doesn't exist
php artisan storage:link --force 2>/dev/null || true

# Wait for MariaDB to be ready
echo "Waiting for database..."
until php artisan db:monitor --timeout=1 2>/dev/null; do
    sleep 2
done
echo "Database is ready!"

# Run migrations
php artisan migrate --force

# Seed demo data if database is empty
php artisan db:seed --force 2>/dev/null || true

# Cache config for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start services via supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
