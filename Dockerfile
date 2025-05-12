# Use Node.js base to extract Node and npm
FROM node:22-alpine AS nodejs

# Base image with FrankenPHP
FROM dunglas/frankenphp:php8.3-alpine AS base

# Install required PHP extensions
RUN install-php-extensions \
    ctype \
    curl \
    dom \
    exif \
    fileinfo \
    filter \
    gd \
    hash \
    intl \
    mbstring \
    opcache \
    openssl \
    pcntl \
    pcre \
    pdo \
    pdo_mysql \
    session \
    tokenizer \
    xml \
    zip \
    @composer 

# Copy Node.js and npm binaries from node image
COPY --from=nodejs /usr/local/bin/node /usr/local/bin/
COPY --from=nodejs /usr/local/bin/npm /usr/local/bin/
COPY --from=nodejs /usr/local/lib/node_modules /usr/local/lib/node_modules/

# Set working directory
WORKDIR /app

# Copy application code
COPY . /app

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node.js dependencies and build frontend assets
RUN npm ci
RUN npm run build

# Run Laravel setup
RUN php artisan storage:link

# Run Laravel optimizer
RUN php artisan optimize
RUN php artisan filament:optimize

# Expose port
EXPOSE 8000

# Default command
ENTRYPOINT ["php", "artisan", "octane:frankenphp"]
