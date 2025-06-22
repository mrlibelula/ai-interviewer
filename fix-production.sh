#!/bin/bash
echo "Fixing Laravel production issues..."

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Regenerate optimized files
php artisan config:cache
php artisan route:cache

# Set proper permissions
chmod 755 storage
chmod 755 bootstrap/cache
chmod 644 .env

echo "Done! Try accessing your site now." 