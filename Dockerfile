FROM dunglas/frankenphp:php8.3-alpine AS base

# Install PHP extensions and tools
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
    
# Install Node.js and npm
RUN apk add --no-cache nodejs npm

# Install process manager
RUN npm install -g pm2

# Set working directory
WORKDIR /app

# Copy application code
COPY . /app

# Install PHP dependencies 
RUN composer install --no-dev --optimize-autoloader

# Build frontend assets
RUN npm ci
RUN npm run build

# Laravel setup
RUN php artisan storage:link
RUN php artisan optimize

# Expose FrankenPHP port
EXPOSE 8000

# Start Laravel Octane using FrankenPHP
ENTRYPOINT ["sh", "-c", "pm2 start queue-worker.yml && php artisan octane:frankenphp"]
