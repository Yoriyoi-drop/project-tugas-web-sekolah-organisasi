# ============================================
# Stage 1: Composer (PHP Dependencies)
# ============================================
FROM composer:2 AS composer-stage

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --no-autoloader \
    --no-ansi

RUN composer dump-autoload --optimize --no-dev

# ============================================
# Stage 2: Node (Frontend Build)
# ============================================
FROM node:22-alpine AS node-stage

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm ci

COPY . .

RUN npm run build

# ============================================
# Stage 3: Production (PHP + Nginx)
# ============================================
FROM php:8.4-fpm-alpine AS production

LABEL maintainer="Madrasah Aliyah Nusantara"
LABEL description="Laravel School Organization Web App"

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    sqlite \
    sqlite-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    zip-dev \
    libzip-dev \
    curl-dev \
    icu-dev \
    gmp-dev \
    shadow \
    su-exec \
    tzdata \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_sqlite \
        pdo_mysql \
        mysqli \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        xml \
        zip \
        intl \
        gmp \
        opcache

# Install Redis extension
RUN apk add --no-cache redis-dev && \
    pecl install redis && \
    docker-php-ext-enable redis

# Configure PHP
RUN { \
    echo 'upload_max_filesize=20M'; \
    echo 'post_max_size=25M'; \
    echo 'memory_limit=256M'; \
    echo 'max_execution_time=60'; \
    echo 'max_input_vars=3000'; \
    echo 'date.timezone=Asia/Jakarta'; \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.jit=1255'; \
    echo 'opcache.jit_buffer_size=64M'; \
} > /usr/local/etc/php/conf.d/laravel.ini

# Create app user
RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -S www -G www

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY --chown=www:www . .

# Copy composer dependencies from stage 1
COPY --from=composer-stage --chown=www:www /app/vendor ./vendor

# Copy built frontend from node stage
COPY --from=node-stage --chown=www:www /app/public/build ./public/build

# Create SQLite database file
RUN touch /var/www/html/database/database.sqlite && \
    chown www:www /var/www/html/database/database.sqlite

# Set permissions
RUN chown -R www:www /var/www/html && \
    chmod -R 755 /var/www/html/storage && \
    chmod -R 755 /var/www/html/bootstrap/cache

# Nginx configuration
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

# Supervisor configuration
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Entrypoint script
COPY docker/scripts/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose port
EXPOSE 8000

# Health check
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -f http://localhost:8000/up || exit 1

# Set entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Start supervisor (nginx + php-fpm + queue worker)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
