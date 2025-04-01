I've created a comprehensive SQL schema script that includes all necessary tables, relationships, indexes, and default settings for the aromatherapy e-commerce project. The script is now saved in docs/create_complete_schema.sql. Here's a breakdown of what's included:
Core Tables:
users - User management
categories - Product categorization
products - Product information
product_images - Product image management
moods - Mood profiles
scent_profiles - Scent characteristics
articles - Blog posts and content
article_tags - Content tagging system
Relationship Tables:
product_moods - Product-mood associations
product_scent_profiles - Product-scent profile associations
article_tag - Article-tag associations
E-commerce Tables:
orders - Order management
order_items - Order line items
cart_items - Shopping cart
wishlist_items - User wishlists
product_reviews - Product reviews and ratings
System Tables:
password_reset_tokens - Password reset functionality
failed_jobs - Queue job failures
personal_access_tokens - API authentication
settings - System configuration
Performance Optimizations:
Appropriate indexes on frequently queried columns
Foreign key constraints for data integrity
Full-text search indexes for products and articles
Composite indexes for common queries
Default Settings:
Site configuration
E-commerce settings
Display preferences
Feature toggles
The script uses:
InnoDB engine for transaction support
UTF8MB4 character set for full Unicode support
Appropriate data types and lengths
Proper indexing strategy
Foreign key constraints with CASCADE/SET NULL where appropriate
To use this script in production:
Make sure MySQL 8.0+ is installed
Run the script with appropriate permissions
The script will create the database if it doesn't exist
All tables will be created with proper structure and relationships
Default settings will be inserted
Would you like me to explain any specific part of the schema in more detail?
