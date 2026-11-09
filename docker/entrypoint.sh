#!/bin/sh
set -e

# Update Apache port to match Render's $PORT env if provided
PORT="${PORT:-80}"
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# Cache configurations if APP_KEY is set
if [ -n "$APP_KEY" ]; then
    echo "Caching Laravel configuration..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Run database migrations if DB is configured
if [ -n "$DB_HOST" ] || [ "$DB_CONNECTION" = "sqlite" ]; then
    echo "Running database migrations..."
    php artisan migrate --force || echo "Database migration skipped or failed (check DB credentials)"
    
    # Run seeders if RUN_SEEDER env is set to true
    if [ "$RUN_SEEDER" = "true" ]; then
        echo "Running database seeders..."
        php artisan db:seed --force || echo "Seeder failed or already seeded"
    fi
fi

# Execute main process
exec apache2-foreground
