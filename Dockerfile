FROM php:8.3-fpm

# Install system deps + nginx + supervisor
RUN apt-get update && apt-get install -y \
    nginx supervisor git curl zip unzip \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && pecl install redis && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy all application files
COPY . .

# Remove lock file and install fresh to avoid platform mismatch
RUN rm -f composer.lock \
    && composer install --no-dev --prefer-dist --no-interaction --no-scripts

# Generate optimized autoloader
RUN composer dump-autoload --optimize

# Create required directories
RUN mkdir -p storage/app/public storage/framework/cache/data storage/framework/sessions \
    storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# Nginx config
COPY docker/nginx.conf /etc/nginx/sites-available/default

# PHP config
COPY docker/php.ini /usr/local/etc/php/conf.d/app.ini

# Supervisor config
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Entrypoint
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
