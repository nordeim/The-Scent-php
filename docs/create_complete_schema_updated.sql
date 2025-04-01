-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS aromatherapy_store;
USE aromatherapy_store;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create password reset tokens table
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    email VARCHAR(255) NOT NULL PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create failed jobs table
CREATE TABLE IF NOT EXISTS failed_jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload LONGTEXT NOT NULL,
    exception LONGTEXT NOT NULL,
    failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_failed_jobs_uuid (uuid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create personal access tokens table
CREATE TABLE IF NOT EXISTS personal_access_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    abilities TEXT NULL,
    last_used_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_personal_access_tokens_tokenable_type_tokenable_id (tokenable_type, tokenable_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    parent_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_categories_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    short_description VARCHAR(255) NULL,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    sku VARCHAR(50) NOT NULL UNIQUE,
    product_type ENUM('essential_oil', 'soap') NOT NULL,
    origin_country VARCHAR(100) NULL,
    extraction_method VARCHAR(100) NULL,
    botanical_name VARCHAR(255) NULL,
    safety_notes TEXT NULL,
    usage_instructions TEXT NULL,
    shelf_life VARCHAR(100) NULL,
    is_customizable BOOLEAN DEFAULT FALSE,
    featured BOOLEAN DEFAULT FALSE,
    category_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_products_slug (slug),
    INDEX idx_products_sku (sku),
    INDEX idx_products_featured (featured),
    INDEX idx_products_category (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create product images table
CREATE TABLE IF NOT EXISTS product_images (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product_images_product (product_id),
    INDEX idx_product_images_primary (is_primary),
    INDEX idx_product_images_order (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create moods table
CREATE TABLE IF NOT EXISTS moods (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    icon_class VARCHAR(50) NULL,
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_moods_slug (slug),
    INDEX idx_moods_featured (featured)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create scent profiles table
CREATE TABLE IF NOT EXISTS scent_profiles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    icon_class VARCHAR(50) NULL,
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_scent_profiles_slug (slug),
    INDEX idx_scent_profiles_featured (featured)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create product moods relationship table
CREATE TABLE IF NOT EXISTS product_moods (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    mood_id BIGINT UNSIGNED NOT NULL,
    effectiveness INT UNSIGNED DEFAULT 5,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY unique_product_mood (product_id, mood_id),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (mood_id) REFERENCES moods(id) ON DELETE CASCADE,
    INDEX idx_product_moods_product (product_id),
    INDEX idx_product_moods_mood (mood_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create product scent profiles relationship table
CREATE TABLE IF NOT EXISTS product_scent_profiles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    scent_profile_id BIGINT UNSIGNED NOT NULL,
    intensity INT UNSIGNED DEFAULT 5,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY unique_product_scent (product_id, scent_profile_id),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (scent_profile_id) REFERENCES scent_profiles(id) ON DELETE CASCADE,
    INDEX idx_product_scent_profiles_product (product_id),
    INDEX idx_product_scent_profiles_profile (scent_profile_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create articles table
CREATE TABLE IF NOT EXISTS articles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    excerpt TEXT NULL,
    featured_image VARCHAR(255) NULL,
    author_id BIGINT UNSIGNED NOT NULL,
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_articles_slug (slug),
    INDEX idx_articles_author (author_id),
    INDEX idx_articles_published (published_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create article tags table
CREATE TABLE IF NOT EXISTS article_tags (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_article_tags_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create article tag relationship table
CREATE TABLE IF NOT EXISTS article_tag (
    article_id BIGINT UNSIGNED NOT NULL,
    article_tag_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (article_id, article_tag_id),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (article_tag_id) REFERENCES article_tags(id) ON DELETE CASCADE,
    INDEX idx_article_tag_article (article_id),
    INDEX idx_article_tag_tag (article_tag_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create orders table
CREATE TABLE IF NOT EXISTS orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    total_amount DECIMAL(10,2) NOT NULL,
    shipping_address TEXT NOT NULL,
    billing_address TEXT NOT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_orders_user (user_id),
    INDEX idx_orders_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create order items table
CREATE TABLE IF NOT EXISTS order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_order_items_order (order_id),
    INDEX idx_order_items_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create cart items table
CREATE TABLE IF NOT EXISTS cart_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product (user_id, product_id),
    INDEX idx_cart_items_user (user_id),
    INDEX idx_cart_items_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create wishlist items table
CREATE TABLE IF NOT EXISTS wishlist_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product (user_id, product_id),
    INDEX idx_wishlist_items_user (user_id),
    INDEX idx_wishlist_items_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create product reviews table
CREATE TABLE IF NOT EXISTS product_reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product (user_id, product_id),
    INDEX idx_product_reviews_user (user_id),
    INDEX idx_product_reviews_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create settings table
CREATE TABLE IF NOT EXISTS settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(255) NOT NULL UNIQUE,
    `value` TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_settings_key (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default settings
INSERT INTO settings (`key`, `value`) VALUES
('site_name', 'Aromatherapy Store'),
('site_description', 'Your one-stop shop for aromatherapy products'),
('contact_email', 'contact@aromatherapystore.com'),
('shipping_cost', '5.00'),
('free_shipping_threshold', '50.00'),
('tax_rate', '0.10'),
('currency', 'USD'),
('currency_symbol', '$'),
('maintenance_mode', 'false'),
('allow_registration', 'true'),
('allow_reviews', 'true'),
('require_review_approval', 'true'),
('items_per_page', '12'),
('featured_products_count', '4'),
('featured_articles_count', '3'),
('popular_products_count', '8'),
('popular_tags_count', '10');

-- Create indexes for better performance
CREATE INDEX idx_products_price ON products(price);
CREATE INDEX idx_products_stock ON products(stock);
CREATE INDEX idx_products_created ON products(created_at);
CREATE INDEX idx_products_updated ON products(updated_at);

CREATE INDEX idx_orders_created ON orders(created_at);
CREATE INDEX idx_orders_updated ON orders(updated_at);

CREATE INDEX idx_articles_created ON articles(created_at);
CREATE INDEX idx_articles_updated ON articles(updated_at);

CREATE INDEX idx_product_reviews_rating ON product_reviews(rating);
CREATE INDEX idx_product_reviews_created ON product_reviews(created_at);

-- Add full-text search indexes
ALTER TABLE products ADD FULLTEXT INDEX ft_products_name_description (name, description);
ALTER TABLE articles ADD FULLTEXT INDEX ft_articles_title_content (title, content);
