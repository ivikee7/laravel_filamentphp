# Stage 1: Build PHP dependencies
FROM php:8.4-fpm-alpine AS backend-builder
WORKDIR /var/www

# Install system dependencies & PHP extensions for Laravel/Filament
RUN apk add --no-cache \
    icu-dev libpng-dev libzip-dev zip unzip git \
    && docker-php-ext-install intl gd zip pdo_mysql bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .
RUN composer install --no-dev --optimize-autoloader

# Stage 2: Build Frontend assets (Filament/Vite)
FROM node:22-alpine AS frontend-builder
WORKDIR /var/www
COPY . .
RUN npm install && npm run build

# Stage 3: Final Production Image
FROM php:8.4-fpm-alpine
WORKDIR /var/www

# Copy extensions and binaries from builder
RUN apk add --no-cache icu-dev libpng-dev libzip-dev \
    && docker-php-ext-install intl gd zip pdo_mysql bcmath

# Copy application files
COPY --from=backend-builder /var/www /var/www
COPY --from=frontend-builder /var/www/public /var/www/public

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
