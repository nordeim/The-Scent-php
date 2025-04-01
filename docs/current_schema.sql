
\n-- Table structure for table `article_tag`\n
\n-- Table structure for table `article_tags`\n
\n-- Table structure for table `articles`\n
\n-- Table structure for table `cart_items`\n
\n-- Table structure for table `categories`\n
\n-- Table structure for table `failed_jobs`\n
\n-- Table structure for table `moods`\n
\n-- Table structure for table `order_items`\n
\n-- Table structure for table `orders`\n
\n-- Table structure for table `password_reset_tokens`\n
\n-- Table structure for table `personal_access_tokens`\n
\n-- Table structure for table `product_images`\n
\n-- Table structure for table `product_moods`\n
\n-- Table structure for table `product_reviews`\n
\n-- Table structure for table `product_scent_profiles`\n
\n-- Table structure for table `products`\n
\n-- Table structure for table `scent_profiles`\n
\n-- Table structure for table `settings`\n
\n-- Table structure for table `users`\n
\n-- Table structure for table `wishlist_items`\n
Tables_in_aromatherapy_store
article_tag
article_tags
articles
cart_items
categories
failed_jobs
moods
order_items
orders
password_reset_tokens
personal_access_tokens
product_images
product_moods
product_reviews
product_scent_profiles
products
scent_profiles
settings
users
wishlist_items

SHOW CREATE TABLE `article_tag`;
SHOW CREATE TABLE `article_tags`;
SHOW CREATE TABLE `articles`;
SHOW CREATE TABLE `cart_items`;
SHOW CREATE TABLE `categories`;
SHOW CREATE TABLE `failed_jobs`;
SHOW CREATE TABLE `moods`;
SHOW CREATE TABLE `order_items`;
SHOW CREATE TABLE `orders`;
SHOW CREATE TABLE `password_reset_tokens`;
SHOW CREATE TABLE `personal_access_tokens`;
SHOW CREATE TABLE `product_images`;
SHOW CREATE TABLE `product_moods`;
SHOW CREATE TABLE `product_reviews`;
SHOW CREATE TABLE `product_scent_profiles`;
SHOW CREATE TABLE `products`;
SHOW CREATE TABLE `scent_profiles`;
SHOW CREATE TABLE `settings`;
SHOW CREATE TABLE `users`;
SHOW CREATE TABLE `wishlist_items`;

\n-- Columns for table `article_tag`\nDESCRIBE `article_tag`;
\n-- Columns for table `article_tags`\nDESCRIBE `article_tags`;
\n-- Columns for table `articles`\nDESCRIBE `articles`;
\n-- Columns for table `cart_items`\nDESCRIBE `cart_items`;
\n-- Columns for table `categories`\nDESCRIBE `categories`;
\n-- Columns for table `failed_jobs`\nDESCRIBE `failed_jobs`;
\n-- Columns for table `moods`\nDESCRIBE `moods`;
\n-- Columns for table `order_items`\nDESCRIBE `order_items`;
\n-- Columns for table `orders`\nDESCRIBE `orders`;
\n-- Columns for table `password_reset_tokens`\nDESCRIBE `password_reset_tokens`;
\n-- Columns for table `personal_access_tokens`\nDESCRIBE `personal_access_tokens`;
\n-- Columns for table `product_images`\nDESCRIBE `product_images`;
\n-- Columns for table `product_moods`\nDESCRIBE `product_moods`;
\n-- Columns for table `product_reviews`\nDESCRIBE `product_reviews`;
\n-- Columns for table `product_scent_profiles`\nDESCRIBE `product_scent_profiles`;
\n-- Columns for table `products`\nDESCRIBE `products`;
\n-- Columns for table `scent_profiles`\nDESCRIBE `scent_profiles`;
\n-- Columns for table `settings`\nDESCRIBE `settings`;
\n-- Columns for table `users`\nDESCRIBE `users`;
\n-- Columns for table `wishlist_items`\nDESCRIBE `wishlist_items`;

\n-- Indexes for table `article_tag`\nSHOW INDEX FROM `article_tag`;
\n-- Indexes for table `article_tags`\nSHOW INDEX FROM `article_tags`;
\n-- Indexes for table `articles`\nSHOW INDEX FROM `articles`;
\n-- Indexes for table `cart_items`\nSHOW INDEX FROM `cart_items`;
\n-- Indexes for table `categories`\nSHOW INDEX FROM `categories`;
\n-- Indexes for table `failed_jobs`\nSHOW INDEX FROM `failed_jobs`;
\n-- Indexes for table `moods`\nSHOW INDEX FROM `moods`;
\n-- Indexes for table `order_items`\nSHOW INDEX FROM `order_items`;
\n-- Indexes for table `orders`\nSHOW INDEX FROM `orders`;
\n-- Indexes for table `password_reset_tokens`\nSHOW INDEX FROM `password_reset_tokens`;
\n-- Indexes for table `personal_access_tokens`\nSHOW INDEX FROM `personal_access_tokens`;
\n-- Indexes for table `product_images`\nSHOW INDEX FROM `product_images`;
\n-- Indexes for table `product_moods`\nSHOW INDEX FROM `product_moods`;
\n-- Indexes for table `product_reviews`\nSHOW INDEX FROM `product_reviews`;
\n-- Indexes for table `product_scent_profiles`\nSHOW INDEX FROM `product_scent_profiles`;
\n-- Indexes for table `products`\nSHOW INDEX FROM `products`;
\n-- Indexes for table `scent_profiles`\nSHOW INDEX FROM `scent_profiles`;
\n-- Indexes for table `settings`\nSHOW INDEX FROM `settings`;
\n-- Indexes for table `users`\nSHOW INDEX FROM `users`;
\n-- Indexes for table `wishlist_items`\nSHOW INDEX FROM `wishlist_items`;
	
\n-- Foreign keys for table `article_tag`	SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'aromatherapy_store' AND TABLE_NAME = 'article_tag' AND REFERENCED_TABLE_NAME IS NOT NULL;
\n-- Foreign keys for table `article_tags`	SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'aromatherapy_store' AND TABLE_NAME = 'article_tags' AND REFERENCED_TABLE_NAME IS NOT NULL;
\n-- Foreign keys for table `articles`	SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'aromatherapy_store' AND TABLE_NAME = 'articles' AND REFERENCED_TABLE_NAME IS NOT NULL;
\n-- Foreign keys for table `cart_items`	SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'aromatherapy_store' AND TABLE_NAME = 'cart_items' AND REFERENCED_TABLE_NAME IS NOT NULL;
\n-- Foreign keys for table `categories`	SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'aromatherapy_store' AND TABLE_NAME = 'categories' AND REFERENCED_TABLE_NAME IS NOT NULL;
\n-- Foreign keys for table `failed_jobs`	SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'aromatherapy_store' AND TABLE_NAME = 'failed_jobs' AND REFERENCED_TABLE_NAME IS NOT NULL;
\n-- Foreign keys for table `moods`	SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'aromatherapy_store' AND TABLE_NAME = 'moods' AND REFERENCED_TABLE_NAME IS NOT NULL;
\n-- Foreign keys for table `order_items`	SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'aromatherapy_store' AND TABLE_NAME = 'order_items' AND REFERENCED_TABLE_NAME IS NOT NULL;
\n-- Foreign keys for table `orders`	SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'aromatherapy_store' AND TABLE_NAME = 'orders' AND REFERENCED_TABLE_NAME IS NOT NULL;
\n-- Foreign keys for table `password_reset_tokens`	SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'aromatherapy_store' AND TABLE_NAME = 'password_reset_tokens' AND REFERENCED_TABLE_NAME IS NOT NULL;
\n-- Foreign keys for table `personal_access_tokens`	SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'aromatherapy_store' AND TABLE_NAME = 'personal_access_tokens' AND REFERENCED_TABLE_NAME IS NOT NULL;
\n-- Foreign keys for table `product_images`	SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'aromatherapy_store' AND TABLE_NAME = 'product_images' AND REFERENCED_TABLE_NAME IS NOT NULL;
\n-- Foreign keys for table `product_moods`	SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'aromatherapy_store' AND TABLE_NAME = 'product_moods' AND REFERENCED_TABLE_NAME IS NOT NULL;
\n-- Foreign keys for table `product_reviews`	SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'aromatherapy_store' AND TABLE_NAME = 'product_reviews' AND REFERENCED_TABLE_NAME IS NOT NULL;
\n-- Foreign keys for table `product_scent_profiles`	SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'aromatherapy_store' AND TABLE_NAME = 'product_scent_profiles' AND REFERENCED_TABLE_NAME IS NOT NULL;
\n-- Foreign keys for table `products`	SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'aromatherapy_store' AND TABLE_NAME = 'products' AND REFERENCED_TABLE_NAME IS NOT NULL;
\n-- Foreign keys for table `scent_profiles`	SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'aromatherapy_store' AND TABLE_NAME = 'scent_profiles' AND REFERENCED_TABLE_NAME IS NOT NULL;
\n-- Foreign keys for table `settings`	SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'aromatherapy_store' AND TABLE_NAME = 'settings' AND REFERENCED_TABLE_NAME IS NOT NULL;
\n-- Foreign keys for table `users`	SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'aromatherapy_store' AND TABLE_NAME = 'users' AND REFERENCED_TABLE_NAME IS NOT NULL;
\n-- Foreign keys for table `wishlist_items`	SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = 'aromatherapy_store' AND TABLE_NAME = 'wishlist_items' AND REFERENCED_TABLE_NAME IS NOT NULL;
-- Triggers
\n-- Triggers
-- Views
\n-- Views
-- Routines (Stored Procedures and Functions)
\n-- Routines (Stored Procedures and Functions)
