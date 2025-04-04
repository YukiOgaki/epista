#!/usr/bin/env bash

echo "=== Installing Node modules and building assets ==="
npm install
npm run build

echo "=== Installing PHP dependencies ==="
composer install --no-dev --working-dir=/var/www/html

echo "=== Generating application key ==="
php artisan key:generate --force

echo "=== Caching configuration ==="
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Running database migrations ==="
php artisan migrate --force
