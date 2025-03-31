# Installation Guide: Aromatherapy E-commerce Platform

## Table of Contents
1. [Project Structure](#project-structure)
2. [Server Prerequisites](#server-prerequisites)
3. [Installation Steps](#installation-steps)
4. [Apache2 Configuration](#apache2-configuration)
5. [PHP Configuration](#php-configuration)
6. [Application Setup](#application-setup)
7. [Database Setup](#database-setup)
8. [Security Configuration](#security-configuration)
9. [Starting the Application](#starting-the-application)
10. [Troubleshooting](#troubleshooting)

## Project Structure

```
/var/www/aromatherapy-store/
├── app/                    # Application core code
├── bootstrap/             # Application bootstrap files
├── config/                # Configuration files
├── database/              # Database migrations and seeders
├── public/                # Publicly accessible files
├── resources/             # Frontend resources
├── routes/                # Application routes
├── storage/               # Application storage
├── tests/                 # Test files
├── vendor/                # Composer dependencies
├── .env                   # Environment configuration
├── .env.example          # Example environment file
├── artisan               # Laravel command-line tool
├── composer.json         # Composer configuration
├── package.json          # NPM configuration
└── README.md             # Project documentation
```

## Server Prerequisites

### Required Packages
```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install Apache2 and PHP 8.2 with required extensions
sudo apt install -y apache2 php8.2 php8.2-cli php8.2-common php8.2-mysql \
    php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath \
    php8.2-fpm php8.2-intl php8.2-opcache php8.2-readline php8.2-sqlite3 \
    php8.2-tokenizer php8.2-json php8.2-ldap php8.2-curl php8.2-zip

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js and npm
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Install Git
sudo apt install -y git
```

### Enable Required Apache Modules
```bash
sudo a2enmod rewrite
sudo a2enmod headers
sudo a2enmod ssl
sudo a2enmod proxy_fcgi
sudo a2enmod setenvif
```

## Installation Steps

1. Create project directory and set permissions
```bash
sudo mkdir -p /var/www/aromatherapy-store
sudo chown -R $USER:$USER /var/www/aromatherapy-store
sudo chmod -R 755 /var/www/aromatherapy-store
```

2. Clone the project repository
```bash
cd /var/www/aromatherapy-store
git clone https://github.com/yourusername/aromatherapy-store.git .
```

3. Install PHP dependencies
```bash
composer install --no-dev --optimize-autoloader
```

4. Install Node.js dependencies and build assets
```bash
npm install
npm run build
```

5. Set up environment file
```bash
cp .env.example .env
php artisan key:generate
```

6. Configure environment variables
```bash
# Edit .env file with your production settings
nano .env
```

Required environment variables:
```env
APP_NAME="Aromatherapy Store"
APP_ENV=production
APP_KEY=your-generated-key
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aromatherapy_store
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@domain.com
MAIL_FROM_NAME="${APP_NAME}"
```

7. Set proper permissions
```bash
sudo chown -R www-data:www-data /var/www/aromatherapy-store
sudo chmod -R 755 /var/www/aromatherapy-store
sudo chmod -R 775 /var/www/aromatherapy-store/storage
sudo chmod -R 775 /var/www/aromatherapy-store/bootstrap/cache
```

## Apache2 Configuration

1. Create Apache virtual host configuration
```bash
sudo nano /etc/apache2/sites-available/aromatherapy-store.conf
```

Add the following configuration:
```apache
<VirtualHost *:80>
    ServerName your-domain.com
    ServerAlias www.your-domain.com
    DocumentRoot /var/www/aromatherapy-store/public

    <Directory /var/www/aromatherapy-store/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/aromatherapy-store-error.log
    CustomLog ${APACHE_LOG_DIR}/aromatherapy-store-access.log combined

    # Enable HTTP/2
    Protocols h2 http/1.1

    # Security headers
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set X-Content-Type-Options "nosniff"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</VirtualHost>
```

2. Enable the site
```bash
sudo a2ensite aromatherapy-store.conf
sudo a2dissite 000-default.conf
```

## PHP Configuration

1. Configure PHP for production
```bash
sudo nano /etc/php/8.2/apache2/php.ini
```

Update the following settings:
```ini
memory_limit = 256M
upload_max_filesize = 64M
post_max_size = 64M
max_execution_time = 300
max_input_time = 300
default_socket_timeout = 3600
date.timezone = "UTC"
```

2. Configure PHP-FPM
```bash
sudo nano /etc/php/8.2/fpm/pool.d/www.conf
```

Update the following settings:
```ini
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500
```

## Application Setup

1. Generate application key
```bash
php artisan key:generate
```

2. Clear configuration cache
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. Optimize autoloader
```bash
composer dump-autoload --optimize
```

4. Set up storage links
```bash
php artisan storage:link
```

## Database Setup

1. Create MySQL database and user
```bash
sudo mysql -u root -p
```

```sql
CREATE DATABASE aromatherapy_store;
CREATE USER 'aromatherapy_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON aromatherapy_store.* TO 'aromatherapy_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

2. Import database schema
```bash
mysql -u aromatherapy_user -p aromatherapy_store < docs/create_complete_schema.sql
```

3. Run migrations and seeders
```bash
php artisan migrate
php artisan db:seed
```

## Security Configuration

1. Set up SSL certificate (using Let's Encrypt)
```bash
sudo apt install certbot python3-certbot-apache
sudo certbot --apache -d your-domain.com -d www.your-domain.com
```

2. Configure firewall
```bash
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

3. Set up fail2ban
```bash
sudo apt install fail2ban
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

## Starting the Application

1. Restart PHP-FPM
```bash
sudo systemctl restart php8.2-fpm
```

2. Restart Apache2
```bash
sudo systemctl restart apache2
```

3. Check application status
```bash
sudo systemctl status apache2
sudo systemctl status php8.2-fpm
```

4. Monitor error logs
```bash
sudo tail -f /var/log/apache2/aromatherapy-store-error.log
```

## Troubleshooting

### Common Issues and Solutions

1. Permission Issues
```bash
# Fix storage permissions
sudo chown -R www-data:www-data /var/www/aromatherapy-store/storage
sudo chmod -R 775 /var/www/aromatherapy-store/storage

# Fix cache permissions
sudo chown -R www-data:www-data /var/www/aromatherapy-store/bootstrap/cache
sudo chmod -R 775 /var/www/aromatherapy-store/bootstrap/cache
```

2. Apache Configuration Issues
```bash
# Check Apache configuration
sudo apache2ctl configtest

# Check Apache error logs
sudo tail -f /var/log/apache2/error.log
```

3. PHP Configuration Issues
```bash
# Check PHP configuration
php -i

# Check PHP-FPM logs
sudo tail -f /var/log/php8.2-fpm.log
```

4. Database Connection Issues
```bash
# Test database connection
php artisan db:monitor

# Check database logs
sudo tail -f /var/log/mysql/error.log
```

### Maintenance Commands

1. Clear application cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

2. Optimize application
```bash
php artisan optimize
php artisan view:cache
php artisan route:cache
php artisan config:cache
```

3. Check application status
```bash
php artisan --version
php artisan env
```

### Backup Procedures

1. Database backup
```bash
mysqldump -u aromatherapy_user -p aromatherapy_store > backup.sql
```

2. Application backup
```bash
tar -czf aromatherapy-store-backup.tar.gz /var/www/aromatherapy-store/
```

### Monitoring

1. Check server resources
```bash
# CPU and memory usage
htop

# Disk usage
df -h

# Apache status
sudo apache2ctl status
```

2. Monitor application logs
```bash
# Laravel logs
tail -f /var/www/aromatherapy-store/storage/logs/laravel.log

# Apache logs
tail -f /var/log/apache2/aromatherapy-store-access.log
tail -f /var/log/apache2/aromatherapy-store-error.log
``` 