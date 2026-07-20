#!/bin/bash
set -e

echo "=== Starting Laravel application ==="

# Use PORT from Railway environment, default to 8080
export PORT=${PORT:-8080}
echo "PORT: ${PORT}"

# Update Apache to listen on the correct port
# Replace ALL port references in Apache config
sed -i "s/Listen 80$/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/Listen 443/#Listen 443/g" /etc/apache2/ports.conf

echo "Apache ports.conf updated to listen on ${PORT}"

# Ensure storage directories exist with correct permissions
echo "Setting up storage directories..."
mkdir -p /var/www/html/storage/app/public/products
mkdir -p /var/www/html/storage/app/public/news
mkdir -p /var/www/html/storage/app/public/news-content
mkdir -p /var/www/html/storage/app/public/gallery
mkdir -p /var/www/html/storage/app/public/members
mkdir -p /var/www/html/storage/app/public/settings
mkdir -p /var/www/html/storage/app/purifier
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Set permissions
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Create SQLite database if it doesn't exist
DB_PATH="/var/www/html/database/database.sqlite"
FRESH_DB=false
if [ ! -f "$DB_PATH" ]; then
    echo "Creating SQLite database..."
    touch "$DB_PATH"
    FRESH_DB=true
fi

# Make database directory writable (SQLite needs this for WAL journal)
chown -R www-data:www-data /var/www/html/database
chmod -R 775 /var/www/html/database

# Create storage symlink
echo "Creating storage link..."
php artisan storage:link --force 2>/dev/null || true

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "WARNING: APP_KEY not set, generating one..."
    php artisan key:generate --force
fi

# Cache configuration for production
echo "Caching configuration..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Seed database only if it's a fresh database
if [ "$FRESH_DB" = true ]; then
    echo "Seeding database with initial data..."
    php artisan db:seed --force
fi

echo "=== Application ready! Starting Apache on port ${PORT} ==="

# Set Apache environment variable for port
export APACHE_RUN_DIR=/var/run/apache2
export APACHE_RUN_USER=www-data
export APACHE_RUN_GROUP=www-data
export APACHE_LOG_DIR=/var/log/apache2
export APACHE_PID_FILE=/var/run/apache2/apache2.pid

# Ensure conflicting MPM modules are disabled before starting Apache
echo "Enforcing mpm_prefork module..."
a2dismod mpm_event mpm_worker || true
a2enmod mpm_prefork || true

# Start Apache in foreground
exec apache2-foreground
