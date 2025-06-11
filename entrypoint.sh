#!/bin/bash

# Wait for MySQL to be ready
until mysqladmin ping -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
    echo "Waiting for MySQL to be ready..."
    sleep 2
done

# Run migrations
php artisan migrate --force

# Run seeder (check if users table is empty to avoid duplicate seeding)
if [ -z "$(mysql -h $DB_HOST -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE -e 'SELECT 1 FROM users LIMIT 1' 2>/dev/null)" ]; then
    echo "Seeding database..."
    php artisan db:seed --force
else
    echo "Users table already seeded, skipping..."
fi

# Start PHP-FPM
exec php-fpm
