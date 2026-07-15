#!/bin/bash
set -e

echo "🚀 Starting Laravel application..."

# Use PORT from Railway environment, default to 8080
export PORT=${PORT:-8080}

# Update Apache to listen on the correct port
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf

# Ensure storage directories exist with correct permissions
echo "📁 Setting up storage directories..."
mkdir -p /var/www/html/storage/app/public/products
mkdir -p /var/www/html/storage/app/public/news
mkdir -p /var/www/html/storage/app/public/news-content
mkdir -p /var/www/html/storage/app/public/gallery
mkdir -p /var/www/html/storage/app/public/members
mkdir -p /var/www/html/storage/app/public/settings
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
    echo "📦 Creating SQLite database..."
    touch "$DB_PATH"
    chown www-data:www-data "$DB_PATH"
    chmod 664 "$DB_PATH"
    FRESH_DB=true
fi

# Make database directory writable (SQLite needs this for WAL journal)
chmod 775 /var/www/html/database
chown www-data:www-data /var/www/html/database

# Create storage symlink
echo "🔗 Creating storage link..."
php artisan storage:link --force 2>/dev/null || true

# Cache configuration for production
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force

# Seed database only if it's a fresh database
if [ "$FRESH_DB" = true ]; then
    echo "🌱 Seeding database with initial data..."
    php artisan db:seed --force
fi

echo "✅ Application ready! Starting Apache on port ${PORT}..."

# Start Apache in foreground
apache2-foreground
