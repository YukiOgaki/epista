# #!/usr/bin/env bash
# echo "Running composer"
# composer global require hirak/prestissimo
# composer install --no-dev --working-dir=/var/www/html

# echo "Caching config..."
# php artisan config:cache

# echo "Caching routes..."
# php artisan route:cache

# echo "Running migrations..."
# php artisan migrate --force


#!/usr/bin/env bash

echo "=== Installing Node dependencies ==="
npm install

echo "=== Running Vite build ==="
npm run build

echo "=== Installing PHP dependencies ==="
composer install --no-dev --optimize-autoloader

echo "=== Running Artisan setup ==="
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
