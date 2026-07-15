# ============================================
# Stage 1: Build frontend assets (Vite + Tailwind)
# ============================================
FROM node:20-alpine AS frontend-builder

WORKDIR /app

# Copy package files first for better caching
COPY package.json package-lock.json ./
RUN npm ci

# Copy frontend source files
COPY vite.config.js ./
COPY resources/ ./resources/

# Build production assets
RUN npm run build

# ============================================
# Stage 2: PHP / Laravel / Apache
# ============================================
FROM php:8.3-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libsqlite3-dev \
    libzip-dev \
    zip \
    unzip \
    dos2unix \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_sqlite pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Fix MPM conflict: remove all MPM modules, then enable only prefork (required by mod_php)
RUN rm -f /etc/apache2/mods-enabled/mpm_event.conf /etc/apache2/mods-enabled/mpm_event.load \
         /etc/apache2/mods-enabled/mpm_worker.conf /etc/apache2/mods-enabled/mpm_worker.load \
    && a2enmod mpm_prefork rewrite headers

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for dependency caching
COPY composer.json composer.lock ./

# Install PHP dependencies (production only, no dev)
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy application code
COPY . .

# Copy built frontend assets from Stage 1
COPY --from=frontend-builder /app/public/build ./public/build

# Create .env from example (Docker build needs it for artisan commands)
RUN cp .env.example .env

# Generate optimized autoloader
RUN composer dump-autoload --optimize

# Copy Apache configuration
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Copy startup script and fix Windows line endings
COPY docker/start.sh /usr/local/bin/start.sh
RUN dos2unix /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Expose port (Railway uses $PORT)
EXPOSE 8080

# Start application
CMD ["/usr/local/bin/start.sh"]
