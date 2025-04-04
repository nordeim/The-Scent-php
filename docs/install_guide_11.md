# Clear all Laravel caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Clear application cache
php artisan optimize:clear

# Rebuild class autoloader
composer dump-autoload

# Regenerate optimized files for production
php artisan optimize

---
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
composer dump-autoload

php artisan serve
