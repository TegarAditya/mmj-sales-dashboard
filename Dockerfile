FROM dunglas/frankenphp:php8.3-alpine AS base

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

RUN apk add --no-cache nodejs npm

# Set working directory
WORKDIR /app

# Copy application code
COPY . /app

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node.js dependencies and build frontend assets
RUN npm ci
RUN npm run build

# Laravel setup
RUN php artisan storage:link
RUN php artisan optimize
RUN php artisan filament:optimize

# Expose port
EXPOSE 8000

# Start command
ENTRYPOINT ["php", "artisan", "octane:frankenphp"]
