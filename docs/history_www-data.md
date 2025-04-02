cd aromatherapy-store/
# Clear and recache configuration
php artisan config:cache
# Cache routes
php artisan route:cache
# Cache views
php artisan view:cache
# Optimize composer autoloader
composer dump-autoload --optimize
# Create storage symlink
php artisan storage:link
php artisan migrate
cat database/migrations/2024_03_31_000002_create_users_table.php 
php artisan db:show
# Generate migrations for missing tables
php artisan make:migration create_cart_items_table
php artisan make:migration create_orders_table
php artisan make:migration create_order_items_table
php artisan make:migration create_password_reset_tokens_table
php artisan make:migration create_personal_access_tokens_table
php artisan make:migration create_product_reviews_table
php artisan make:migration create_settings_table
php artisan make:migration create_wishlist_items_table
rm database/migrations/2025_04_01_143507_create_cart_items_table.php
rm database/migrations/2025_04_01_143507_create_orders_table.php
rm database/migrations/2025_04_01_143507_create_order_items_table.php
rm database/migrations/2025_04_01_143507_create_password_reset_tokens_table.php
rm database/migrations/2025_04_01_143507_create_personal_access_tokens_table.php
rm database/migrations/2025_04_01_143508_create_product_reviews_table.php
rm database/migrations/2025_04_01_143508_create_settings_table.php
rm database/migrations/2025_04_01_143508_create_wishlist_items_table.php
for f in database/migrations/database_migrations_*.php; do      mv "$f" "database/migrations/$(basename "$f" | sed 's/database_migrations_//')"; done
php artisan migrate:status
php artisan migrate:status
# Get the current maximum batch number
NEXT_BATCH=$((BATCH + 1))
# Insert migration records manually
mysql -u aromatherapy_user -p aromatherapy_store <<EOF
INSERT INTO migrations (migration, batch) VALUES 
('2024_03_31_000002_create_users_table', $NEXT_BATCH),
('2024_03_31_000003_create_categories_table', $NEXT_BATCH),
('2024_03_31_000004_create_products_table', $NEXT_BATCH),
('2024_03_31_000005_create_soap_customization_options_table', $NEXT_BATCH),
('2024_03_31_000006_create_essential_oil_properties_table', $NEXT_BATCH),
('2024_03_31_000007_create_shipping_zones_table', $NEXT_BATCH),
('2024_03_31_000008_create_moods_table', $NEXT_BATCH),
('2024_03_31_000009_create_scent_profiles_table', $NEXT_BATCH),
('2024_03_31_000010_create_product_images_table', $NEXT_BATCH),
('2024_03_31_000011_create_articles_table', $NEXT_BATCH),
('2024_03_31_000012_create_product_moods_table', $NEXT_BATCH),
('2024_03_31_000013_create_product_scent_profiles_table', $NEXT_BATCH),
('2024_03_31_000014_create_article_tags_table', $NEXT_BATCH),
('2024_03_31_000015_create_article_tag_pivot_table', $NEXT_BATCH),
('2024_04_01_000016_create_cache_table', $NEXT_BATCH),
('2024_04_01_000017_create_jobs_table', $NEXT_BATCH),
('2025_04_01_143507_create_cart_items_table', $NEXT_BATCH),
('2025_04_01_143507_create_order_items_table', $NEXT_BATCH),
('2025_04_01_143507_create_orders_table', $NEXT_BATCH),
('2025_04_01_143507_create_password_reset_tokens_table', $NEXT_BATCH),
('2025_04_01_143507_create_personal_access_tokens_table', $NEXT_BATCH),
('2025_04_01_143508_create_product_reviews_table', $NEXT_BATCH),
('2025_04_01_143508_create_settings_table', $NEXT_BATCH),
('2025_04_01_143508_create_wishlist_items_table', $NEXT_BATCH);
EOF

php artisan migrate:status
php artisan db:show
php artisan db:seed
php artisan db:seed
cat database/migrations/database_seeders_DatabaseSeeder.php
mkdir database/seeders
mv database/migrations/database_seeders_DatabaseSeeder.php database/seeders/DatabaseSeeder.php
chown www-data:www-data database/seeders/DatabaseSeeder.php
chmod 644 database/seeders/DatabaseSeeder.php
php artisan db:seed
cat database/seeders/DatabaseSeeder.php
cat /cdrom/project/database_seeders_DatabaseSeeder.php > database/seeders/DatabaseSeeder.php
php artisan db:seed
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear
php artisan view:cache
php artisan storage:link
rm public/storage
cd public
ln -s ../storage/app/public storage
cd ..
php artisan storage:link --relative
chmod -R 775 storage
mkdir -p storage/app/public/products
mkdir -p storage/app/public/avatars
chmod -R 775 storage/app/public
cat /etc/apache2/sites-available/the-scent.com.conf
cat database/migrations/2024_03_31_000001_create_user_roles_table.php
cat database/migrations/2025_04_01_143507_create_cart_items_table.php
more install_guide.md
# Quick check all is well
php artisan --version
php artisan env
php artisan migrate:status
cat .env
# Update MAIL_* settings with actual values
php artisan config:clear
php artisan config:cache
php artisan optimize
'
cd aromatherapy-store/
ls -l
# Clear and recache configuration
php artisan config:cache
# Cache routes
php artisan route:cache
# Cache views
php artisan view:cache
# Optimize composer autoloader
composer dump-autoload --optimize
# Create storage symlink
php artisan storage:link
vi docs/install_guide_2.md 
php artisan migrate
cat database/migrations/2024_03_31_000002_create_users_table.php 
php artisan db:show
# Generate migrations for missing tables
php artisan make:migration create_cart_items_table
php artisan make:migration create_orders_table
php artisan make:migration create_order_items_table
php artisan make:migration create_password_reset_tokens_table
php artisan make:migration create_personal_access_tokens_table
php artisan make:migration create_product_reviews_table
php artisan make:migration create_settings_table
php artisan make:migration create_wishlist_items_table
vi docs/install_guide_2.md 
vi docs/install_guide_2.md 
ls -l docs/
vi docs/install_guide_3.md 
history | grep migrate
rm database/migrations/2025_04_01_143507_create_cart_items_table.php
rm database/migrations/2025_04_01_143507_create_orders_table.php
rm database/migrations/2025_04_01_143507_create_order_items_table.php
rm database/migrations/2025_04_01_143507_create_password_reset_tokens_table.php
rm database/migrations/2025_04_01_143507_create_personal_access_tokens_table.php
rm database/migrations/2025_04_01_143508_create_product_reviews_table.php
rm database/migrations/2025_04_01_143508_create_settings_table.php
rm database/migrations/2025_04_01_143508_create_wishlist_items_table.php
for f in database/migrations/database_migrations_*.php; do      mv "$f" "database/migrations/$(basename "$f" | sed 's/database_migrations_//')"; done
php artisan migrate:status
php artisan migrate:status
# Get the current maximum batch number
BATCH=$(php artisan migrate:status | grep '\[[0-9]*\] Ran' | sed 's/.*\[\([0-9]*\)\].*/\1/' | sort -nr | head -1)
NEXT_BATCH=$((BATCH + 1))
# Insert migration records manually
mysql -u aromatherapy_user -p aromatherapy_store <<EOF
INSERT INTO migrations (migration, batch) VALUES 
('2024_03_31_000002_create_users_table', $NEXT_BATCH),
('2024_03_31_000003_create_categories_table', $NEXT_BATCH),
('2024_03_31_000004_create_products_table', $NEXT_BATCH),
('2024_03_31_000005_create_soap_customization_options_table', $NEXT_BATCH),
('2024_03_31_000006_create_essential_oil_properties_table', $NEXT_BATCH),
('2024_03_31_000007_create_shipping_zones_table', $NEXT_BATCH),
('2024_03_31_000008_create_moods_table', $NEXT_BATCH),
('2024_03_31_000009_create_scent_profiles_table', $NEXT_BATCH),
('2024_03_31_000010_create_product_images_table', $NEXT_BATCH),
('2024_03_31_000011_create_articles_table', $NEXT_BATCH),
('2024_03_31_000012_create_product_moods_table', $NEXT_BATCH),
('2024_03_31_000013_create_product_scent_profiles_table', $NEXT_BATCH),
('2024_03_31_000014_create_article_tags_table', $NEXT_BATCH),
('2024_03_31_000015_create_article_tag_pivot_table', $NEXT_BATCH),
('2024_04_01_000016_create_cache_table', $NEXT_BATCH),
('2024_04_01_000017_create_jobs_table', $NEXT_BATCH),
('2025_04_01_143507_create_cart_items_table', $NEXT_BATCH),
('2025_04_01_143507_create_order_items_table', $NEXT_BATCH),
('2025_04_01_143507_create_orders_table', $NEXT_BATCH),
('2025_04_01_143507_create_password_reset_tokens_table', $NEXT_BATCH),
('2025_04_01_143507_create_personal_access_tokens_table', $NEXT_BATCH),
('2025_04_01_143508_create_product_reviews_table', $NEXT_BATCH),
('2025_04_01_143508_create_settings_table', $NEXT_BATCH),
('2025_04_01_143508_create_wishlist_items_table', $NEXT_BATCH);
EOF

php artisan migrate:status
php artisan db:show
php artisan db:seed
history
php artisan db:seed
ls -l database/migrations/
cat database/migrations/database_seeders_DatabaseSeeder.php
ls -l database/seeders
mkdir database/seeders
mv database/migrations/database_seeders_DatabaseSeeder.php database/seeders/DatabaseSeeder.php
ls -l database/seeders
chown www-data:www-data database/seeders/DatabaseSeeder.php
chmod 644 database/seeders/DatabaseSeeder.php
ls -l database/seeders
php artisan db:seed
ls -l database/seeders/DatabaseSeeder.php 
cat database/seeders/DatabaseSeeder.php
cat /cdrom/project/database_seeders_DatabaseSeeder.php > database/seeders/DatabaseSeeder.php
php artisan db:seed
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear
php artisan view:cache
php artisan storage:link
ls -l public/
rm public/storage
ls -l public/
cd public
ln -s ../storage/app/public storage
cd ..
ls -l public/
ls -l storage/app/public
php artisan storage:link --relative
chmod -R 775 storage
mkdir -p storage/app/public/products
mkdir -p storage/app/public/avatars
chmod -R 775 storage/app/public
ls -ld storage/
ls -ld storage/*
ls -ld storage/app/*
ls -ld storage/* storage/*/* storage/*/*/*
vi docs/install_guide_3.md 
ls -lt docs/
ls -l /etc/apache2/sites-available/the-scent.com.conf 
cat /etc/apache2/sites-available/the-scent.com.conf
cp /etc/apache2/sites-available/the-scent.com.conf docs/
ls -l docs/
history
history > docs/history
vi docs/history
vi docs/install_guide_3.md 
vi docs/install_guide_4.md 
vi docs/install_guide_4.md 
ls -l database/migrations/
cat database/migrations/2024_03_31_000001_create_user_roles_table.php
cat database/migrations/2025_04_01_143507_create_cart_items_table.php
ls -l docs
ls -lt docs
cat docs/setup_mysql_db.md
ls -l docs/
cat docs/extract_schema.sql
ls -lt docs/
head -100 docs/current_schema.sql
vi docs/install_guide_5.md
vi docs/install_guide_5.md
ls -lt docs/
more install_guide.md
more docs/install_guide.md
history | grep composer
history
vi docs/install_guide_5.md 
history | egrep 'composer|artisan|php '
history | egrep 'composer|artisan|php ' | egrep -v 'history|cat |BATCH'
vi docs/install_guide_6.md
cat docs/install_guide_6.md
# Quick check all is well
php artisan --version
php artisan env
php artisan migrate:status
cat .env
# Update MAIL_* settings with actual values
php artisan config:clear
php artisan config:cache
php artisan optimize
history 
history | sed 's/^ ....  /
'
history | sed 's/^ ....  /'
history | sed -e 's/^ ....  /'
history | sed -e 's/^ ....  //'
history | sed -e 's/^ ....  //' | egrep -v 'history|grep|docs|ls '
history | sed -e 's/^ ....  //' | egrep -v 'history|grep|docs|ls ' > ~/.bash_history 
exit
