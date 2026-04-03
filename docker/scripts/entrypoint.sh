#!/bin/sh
set -e

echo "🚀 Starting Laravel Application..."

# Wait for database to be ready
if [ "$DB_CONNECTION" = "mysql" ] && [ -n "$DB_HOST" ]; then
    echo "⏳ Waiting for MySQL to be ready..."
    until nc -z -w 5 "$DB_HOST" "$DB_PORT" 2>/dev/null; do
        echo "   MySQL is unavailable - sleeping 2s"
        sleep 2
    done
    echo "   MySQL is ready!"
fi

# Run migrations if AUTO_MIGRATE is set
if [ "$AUTO_MIGRATE" = "true" ]; then
    echo "📦 Running database migrations..."
    php artisan migrate --force --no-interaction
    echo "   Migrations complete!"
fi

# Optimize for production
if [ "$APP_ENV" = "production" ]; then
    echo "⚡ Optimizing for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    echo "   Optimization complete!"
fi

# Set proper permissions
chown -R www:www /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

echo "✅ Application ready!"

exec "$@"
