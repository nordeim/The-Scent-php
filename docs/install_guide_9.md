# Install eloquent-sluggable
composer require cviebrock/eloquent-sluggable
# Clear composer cache if needed
composer clear-cache
date
# Clear all caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
# Optimize
php artisan optimize
# Publish the sluggable configuration
php artisan vendor:publish --provider="Cviebrock\EloquentSluggable\ServiceProvider"
grep 'use ' app/Models/Product.php 
grep 'use' app/Models/Product.php 
# Enter tinker
php artisan tinker
# Test product creation with slug
>>> $product = new App\Models\Product();
>>> $product->name = "Test Essential Oil";
>>> $product->price = 29.99;
>>> $product->save();
>>> echo $product->slug;  # Should output "test-essential-oil"
mysql -u aromatherapy_user -p aromatherapy_store
mysql -u aromatherapy_user -p aromatherapy_store
id
sudo chown -R www-data:www-data /cdrom/project/aromatherapy-store/storage/psysh
mysql -u aromatherapy_user -p aromatherapy_store
chown -R www-data:www-data /cdrom/project/aromatherapy-store/storage/psysh
php artisan migrate
chmod 777 docs
php artisan config:clear
mysql -u root -p
grep Aroma1234 .env
# Clear cache again
php artisan config:clear
php artisan cache:clear
# Test connection
php artisan tinker
>>> DB::connection()->getPdo();
php artisan config:clear
# Create a test file
<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $pdo = DB::connection()->getPdo();
    echo "Connected successfully to database: " . DB::connection()->getDatabaseName() . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
EOF

# Run the test
php test_db.php
# Create a test file to check env values
<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

echo "DB_CONNECTION: " . env('DB_CONNECTION') . "\n";
echo "DB_HOST: " . env('DB_HOST') . "\n";
echo "DB_PORT: " . env('DB_PORT') . "\n";
echo "DB_DATABASE: " . env('DB_DATABASE') . "\n";
echo "DB_USERNAME: " . env('DB_USERNAME') . "\n";
EOF

# Run the test
php test_env.php
grep ^DB .env
php docs/test_env.php 
mv test_db.php docs/
php docs/test_env.php 
# Create test_env.php in the project root
<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "DB_CONNECTION: " . env('DB_CONNECTION') . "\n";
echo "DB_HOST: " . env('DB_HOST') . "\n";
echo "DB_PORT: " . env('DB_PORT') . "\n";
echo "DB_DATABASE: " . env('DB_DATABASE') . "\n";
echo "DB_USERNAME: " . env('DB_USERNAME') . "\n";
EOF

# Run from project root
php test_env.php
mv test_env.php docs/
# Start tinker
php artisan tinker
# Test product creation with slug
>>> $product = new App\Models\Product();
>>> $product->name = "Test Essential Oil";
>>> $product->price = 29.99;
>>> $product->description = "A test essential oil product";
>>> $product->short_description = "Test oil";
>>> $product->stock = 100;
>>> $product->save();
>>> echo $product->slug;  # Should output "test-essential-oil"
chown -R www-data:www-data storage/psysh
# Set the XDG_CONFIG_HOME environment variable for Tinker
export XDG_CONFIG_HOME=/cdrom/project/aromatherapy-store/storage
php artisan tinker
# In Tinker:
>>> $product = new App\Models\Product();
>>> $product->name = "Test Essential Oil";
>>> $product->price = 29.99;
>>> $product->description = "A test essential oil product";
>>> $product->short_description = "Test oil";
>>> $product->stock = 100;
>>> $product->save();
>>> echo $product->slug;
php test_product.php 
php test_product.php 
php test_product.php 
head -20 docs/install_guide_8.md
id
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
# Regenerate cached files
php artisan optimize
# Clear and regenerate composer's autoload files
composer dump-autoload
# Verify the application is working
php artisan about
php artisan tinker
# In Tinker:
>>> $product = App\Models\Product::find(1);
>>> echo $product->slug;  # Should show "test-essential-oil"
# Try creating another product with a similar name to test slug uniqueness
>>> $product2 = new App\Models\Product();
>>> $product2->name = "Test Essential Oil";
>>> $product2->price = 39.99;
>>> $product2->description = "Another test oil";
>>> $product2->short_description = "Test oil 2";
>>> $product2->stock = 50;
>>> $product2->sku = "TEO-" . rand(1000, 9999);
>>> $product2->product_type = "essential_oil";
>>> $product2->category_id = 1;
>>> $product2->save();
>>> echo $product2->slug;  # Should show "test-essential-oil-2"
php test_slug.php 
php artisan tinker
php artisan tinker
exit
