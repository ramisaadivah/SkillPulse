# --- Stage 1: Build Assets (Node.js) ---
FROM node:20-alpine AS asset-builder
WORKDIR /app

# Leverage Docker cache for dependencies
COPY package*.json ./
RUN npm install

# Copy source and build
COPY . .
RUN npm run build

# --- Stage 2: Production Server (PHP/Apache) ---
FROM php:8.2-apache

# Install system dependencies & clean up cache to keep image small
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 1. Copy the entire application source first
COPY . .

# 2. THE FIX: Copy compiled assets from the asset-builder stage
# This ensures public/build/manifest.json exists for Vite
COPY --from=asset-builder /app/public/build ./public/build

# 3. Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# 4. Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configure Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && a2enmod rewrite

EXPOSE 80