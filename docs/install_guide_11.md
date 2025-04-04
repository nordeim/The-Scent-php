```bash
# Clear node_modules and lock files to ensure clean state
rm -rf node_modules package-lock.json

# Reinstall dependencies
npm install

# Build the assets
npm run build
```

```
npm install -D @tailwindcss/forms @tailwindcss/aspect-ratio

# Install dependencies again
npm install

# Clear NPM cache if needed
npm cache clean --force

# Rebuild assets
npm run build

# Clear Laravel caches
php artisan optimize:clear

# Rebuild caches for production
php artisan optimize
```

```bash
# First install/update dependencies
npm install
composer install

# Build frontend assets
npm run build

# Clear all Laravel caches as you mentioned
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# Rebuild autoloader
composer dump-autoload

# Link storage
php artisan storage:link

# Optional but recommended for production
php artisan optimize
php artisan view:cache
php artisan config:cache

# If using route model binding
php artisan route:cache
```

---
```bash
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
```

---
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
composer dump-autoload

php artisan serve
```
