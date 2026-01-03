# --- Stage 1: Build Assets (Node.js) ---
FROM node:20 AS asset-builder
WORKDIR /app
# Copy only package files first to leverage Docker cache
COPY package*.json ./
RUN npm install
# Copy the rest of your frontend code
COPY . .
# Generate the /public/build/manifest.json file
RUN npm run build

# --- Stage 2: Production Server (PHP/Apache) ---
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy your entire project code
COPY . .

# Copy the COMPILED assets from the first stage (This is the fix!)
COPY --from=asset-builder /app/public/build ./public/build

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set correct permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configure Apache to point to Laravel's /public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite
RUN touch /var/www/html/database/database.sqlite && \
    chown www-data:www-data /var/www/html/database/database.sqlite

# Create a small script to run migrations before starting Apache
RUN echo '#!/bin/sh\nphp artisan migrate --force\nexec apache2-foreground' > /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

# Use the script as the entrypoint
CMD ["/usr/local/bin/start.sh"]

EXPOSE 80