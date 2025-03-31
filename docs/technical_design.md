# Technical Design Specification: Aromatherapy E-commerce Platform

## Table of Contents
1. [Project Overview](#project-overview)
2. [System Architecture](#system-architecture)
3. [Database Design](#database-design)
4. [Models and Relationships](#models-and-relationships)
5. [Controllers and Business Logic](#controllers-and-business-logic)
6. [Frontend Architecture](#frontend-architecture)
7. [Security Considerations](#security-considerations)
8. [Performance Optimization](#performance-optimization)
9. [Testing Strategy](#testing-strategy)
10. [Deployment Guide](#deployment-guide)
11. [Conclusion](#conclusion)
12. [Appendix](#appendix)

## Project Overview

### Requirements
The aromatherapy e-commerce platform is designed to provide a comprehensive solution for selling aromatherapy products, including essential oils, soaps, and related items. The system must support:

1. Product Management
   - Detailed product information
   - Multiple product images
   - Product categorization
   - Stock management
   - Price management
   - Product customization options

2. Mood and Scent Profile Integration
   - Mood-based product recommendations
   - Scent profile categorization
   - Effectiveness and intensity ratings
   - Related product suggestions

3. Content Management
   - Educational articles
   - Blog posts
   - Product guides
   - Tag-based content organization

4. User Experience
   - Intuitive navigation
   - Advanced search capabilities
   - Personalized recommendations
   - Mobile responsiveness

### Implementation Approach
The project is implemented using:
- Laravel 10.x PHP framework
- MySQL 8.0 database
- Apache2 web server
- Modern frontend technologies (Tailwind CSS, Alpine.js)
- RESTful API architecture

## System Architecture

### Technology Stack
```
Backend:
- PHP 8.2+
- Laravel 10.x
- MySQL 8.0
- Apache2

Frontend:
- HTML5
- CSS3 (Tailwind CSS)
- JavaScript (Alpine.js)
- Blade templating engine

Development Tools:
- Composer
- npm
- Git
```

### Directory Structure
```
project/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   └── Services/
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
├── routes/
└── tests/
```

## Database Design

### Core Tables

#### Products Table
```sql
CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT NOT NULL,
    short_description VARCHAR(255),
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    sku VARCHAR(50) UNIQUE NOT NULL,
    product_type ENUM('essential_oil', 'soap') NOT NULL,
    origin_country VARCHAR(100),
    extraction_method VARCHAR(100),
    botanical_name VARCHAR(255),
    safety_notes TEXT,
    usage_instructions TEXT,
    shelf_life VARCHAR(100),
    is_customizable BOOLEAN DEFAULT FALSE,
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

#### Moods Table
```sql
CREATE TABLE moods (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT NOT NULL,
    icon_class VARCHAR(50) NULL,
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_featured (featured)
);
```

#### Scent Profiles Table
```sql
CREATE TABLE scent_profiles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT NOT NULL,
    icon_class VARCHAR(50) NULL,
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_featured (featured)
);
```

### Relationship Tables

#### Product-Mood Relationship
```sql
CREATE TABLE product_moods (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    mood_id BIGINT UNSIGNED NOT NULL,
    effectiveness INT UNSIGNED DEFAULT 5,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY unique_product_mood (product_id, mood_id),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (mood_id) REFERENCES moods(id) ON DELETE CASCADE,
    INDEX idx_product_mood (product_id, mood_id)
);
```

#### Product-Scent Profile Relationship
```sql
CREATE TABLE product_scent_profiles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    scent_profile_id BIGINT UNSIGNED NOT NULL,
    intensity INT UNSIGNED DEFAULT 5,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY unique_product_scent (product_id, scent_profile_id),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (scent_profile_id) REFERENCES scent_profiles(id) ON DELETE CASCADE,
    INDEX idx_product_scent (product_id, scent_profile_id)
);
```

## Models and Relationships

### Product Model
The Product model represents the core entity of the e-commerce system. It includes relationships with moods, scent profiles, and images.

```php
class Product extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'name', 'slug', 'price', 'description', 'short_description',
        'image_url', 'featured', 'category_id', 'stock', 'sku',
        'product_type', 'origin_country', 'extraction_method', 'botanical_name',
        'safety_notes', 'usage_instructions', 'shelf_life', 'is_customizable'
    ];

    protected $casts = [
        'featured' => 'boolean',
        'is_customizable' => 'boolean',
        'price' => 'decimal:2',
        'stock' => 'integer'
    ];

    public function moods()
    {
        return $this->belongsToMany(Mood::class, 'product_moods')
                    ->withPivot('effectiveness')
                    ->withTimestamps();
    }

    public function scentProfiles()
    {
        return $this->belongsToMany(ScentProfile::class, 'product_scent_profiles')
                    ->withPivot('intensity')
                    ->withTimestamps();
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->ordered();
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->primary();
    }
}
```

### Mood Model
The Mood model represents different emotional states that products can address.

```php
class Mood extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'name', 'slug', 'description', 'icon_class', 'featured'
    ];

    protected $casts = [
        'featured' => 'boolean'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_moods')
                    ->withPivot('effectiveness')
                    ->withTimestamps();
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }
}
```

### Scent Profile Model
The Scent Profile model represents different scent characteristics of products.

```php
class ScentProfile extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'name', 'slug', 'description', 'icon_class', 'featured'
    ];

    protected $casts = [
        'featured' => 'boolean'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_scent_profiles')
                    ->withPivot('intensity')
                    ->withTimestamps();
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }
}
```

## Controllers and Business Logic

### ProductController
The ProductController handles all product-related operations, including listing, creation, updating, and deletion.

```php
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'primaryImage'])
            ->active();

        // Filter by mood
        if ($request->has('mood')) {
            $query->whereHas('moods', function($q) use ($request) {
                $q->where('slug', $request->mood);
            });
        }

        // Filter by scent profile
        if ($request->has('scent')) {
            $query->whereHas('scentProfiles', function($q) use ($request) {
                $q->where('slug', $request->scent);
            });
        }

        // Sort products
        $sort = $request->get('sort', 'featured');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            default:
                $query->featured();
        }

        return $query->paginate(12);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'moods' => 'array',
            'moods.*' => 'exists:moods,id',
            'scent_profiles' => 'array',
            'scent_profiles.*' => 'exists:scent_profiles,id'
        ]);

        $product = Product::create($validated);

        // Attach moods with effectiveness
        if ($request->has('moods')) {
            foreach ($request->moods as $moodId => $effectiveness) {
                $product->moods()->attach($moodId, ['effectiveness' => $effectiveness]);
            }
        }

        // Attach scent profiles with intensity
        if ($request->has('scent_profiles')) {
            foreach ($request->scent_profiles as $profileId => $intensity) {
                $product->scentProfiles()->attach($profileId, ['intensity' => $intensity]);
            }
        }

        return redirect()->route('products.show', $product)
            ->with('success', 'Product created successfully.');
    }
}
```

### MoodController
The MoodController manages mood profiles and their relationships with products.

```php
class MoodController extends Controller
{
    public function index()
    {
        $moods = Mood::withCount('products')
            ->orderBy('featured', 'desc')
            ->orderBy('name')
            ->get();

        return view('moods.index', compact('moods'));
    }

    public function show(Mood $mood)
    {
        $products = Product::with(['category', 'primaryImage'])
            ->whereHas('moods', function($query) use ($mood) {
                $query->where('id', $mood->id);
            })
            ->active()
            ->orderByPivot('effectiveness', 'desc')
            ->paginate(12);

        return view('moods.show', compact('mood', 'products'));
    }
}
```

### ScentProfileController
The ScentProfileController manages scent profiles and their relationships with products.

```php
class ScentProfileController extends Controller
{
    public function index()
    {
        $profiles = ScentProfile::withCount('products')
            ->orderBy('featured', 'desc')
            ->orderBy('name')
            ->get();

        return view('scent-profiles.index', compact('profiles'));
    }

    public function show(ScentProfile $profile)
    {
        $products = Product::with(['category', 'primaryImage'])
            ->whereHas('scentProfiles', function($query) use ($profile) {
                $query->where('id', $profile->id);
            })
            ->active()
            ->orderByPivot('intensity', 'desc')
            ->paginate(12);

        return view('scent-profiles.show', compact('profile', 'products'));
    }
}
```

## Frontend Architecture

### Blade Templates
The application uses Laravel's Blade templating engine for views. Key templates include:

1. Layout Template
```php
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Aromatherapy Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav>
        <!-- Navigation -->
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <!-- Footer -->
    </footer>
</body>
</html>
```

2. Product Card Component
```php
<div class="product-card">
    <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}">
    <h3>{{ $product->name }}</h3>
    <p class="price">{{ $product->formatted_price }}</p>
    <div class="moods">
        @foreach($product->moods as $mood)
            <span class="mood-tag">{{ $mood->name }}</span>
        @endforeach
    </div>
    <div class="scent-profiles">
        @foreach($product->scentProfiles as $profile)
            <span class="scent-tag">{{ $profile->name }}</span>
        @endforeach
    </div>
</div>
```

### JavaScript Components
The application uses Alpine.js for interactive components:

```javascript
// Product Quick View
document.addEventListener('alpine:init', () => {
    Alpine.data('productQuickView', () => ({
        show: false,
        product: null,
        async loadProduct(id) {
            const response = await fetch(`/api/products/${id}`);
            this.product = await response.json();
            this.show = true;
        }
    }));
});
```

## Security Considerations

1. Input Validation
```php
// ProductController
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0'
    ]);
}
```

2. CSRF Protection
```php
// VerifyCsrfToken middleware
protected $except = [
    'api/*'
];
```

3. XSS Prevention
```php
// In Blade templates
{{ e($userInput) }}
```

## Performance Optimization

1. Database Indexing
```sql
-- Products table
CREATE INDEX idx_featured ON products(featured);
CREATE INDEX idx_category ON products(category_id);

-- Product-Mood relationship
CREATE INDEX idx_product_mood ON product_moods(product_id, mood_id);
```

2. Eager Loading
```php
$products = Product::with(['category', 'primaryImage', 'moods', 'scentProfiles'])
    ->active()
    ->paginate(12);
```

3. Caching
```php
// Cache frequently accessed data
$featuredProducts = Cache::remember('featured_products', 3600, function () {
    return Product::featured()->take(4)->get();
});
```

## Testing Strategy

1. Unit Tests
```php
class ProductTest extends TestCase
{
    public function test_product_can_be_created()
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 29.99
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 29.99
        ]);
    }
}
```

2. Feature Tests
```php
class ProductListingTest extends TestCase
{
    public function test_products_can_be_filtered_by_mood()
    {
        $mood = Mood::factory()->create();
        $product = Product::factory()->create();
        $product->moods()->attach($mood->id);

        $response = $this->get("/products?mood={$mood->slug}");

        $response->assertSee($product->name);
    }
}
```

## Deployment Guide

### Server Requirements
- PHP 8.2 or higher
- MySQL 8.0 or higher
- Apache2 with mod_rewrite enabled
- Composer
- Node.js and npm

### Installation Steps

1. Clone the repository
```bash
git clone https://github.com/yourusername/aromatherapy-store.git
cd aromatherapy-store
```

2. Install dependencies
```bash
composer install
npm install
```

3. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database
```bash
php artisan migrate
php artisan db:seed
```

5. Build assets
```bash
npm run build
```

6. Configure Apache
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/aromatherapy-store/public

    <Directory /var/www/aromatherapy-store/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

## Conclusion

The aromatherapy e-commerce platform has been successfully implemented with a robust architecture that supports all required features. The system is built on Laravel's best practices and includes:

1. Comprehensive product management
2. Mood and scent profile integration
3. Content management system
4. Responsive frontend design
5. Security measures
6. Performance optimizations
7. Testing infrastructure

### Current State
- All core models and relationships are implemented
- Controllers handle all necessary business logic
- Database schema is optimized for performance
- Frontend components are modular and reusable
- Security measures are in place
- Testing framework is set up

### Recommendations
1. Implement real-time inventory updates
2. Add advanced analytics
3. Integrate payment gateways
4. Implement user reviews and ratings
5. Add product comparison feature
6. Implement wishlist functionality
7. Add email marketing integration
8. Implement advanced search with filters
9. Add product bundles feature
10. Implement loyalty program

## Appendix

### Database Initialization

1. Create MySQL database
```sql
CREATE DATABASE aromatherapy_store;
CREATE USER 'aromatherapy_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON aromatherapy_store.* TO 'aromatherapy_user'@'localhost';
FLUSH PRIVILEGES;
```

2. Run migrations
```bash
php artisan migrate
```

3. Seed initial data
```bash
php artisan db:seed
```

### Apache2 Configuration

1. Install required modules
```bash
sudo apt-get update
sudo apt-get install apache2 php8.2 libapache2-mod-php8.2
sudo a2enmod rewrite
sudo a2enmod php8.2
```

2. Configure virtual host
```bash
sudo nano /etc/apache2/sites-available/aromatherapy-store.conf
```

Add the following configuration:
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/aromatherapy-store/public

    <Directory /var/www/aromatherapy-store/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/aromatherapy-store-error.log
    CustomLog ${APACHE_LOG_DIR}/aromatherapy-store-access.log combined
</VirtualHost>
```

3. Enable the site
```bash
sudo a2ensite aromatherapy-store.conf
sudo systemctl restart apache2
```

4. Set proper permissions
```bash
sudo chown -R www-data:www-data /var/www/aromatherapy-store
sudo chmod -R 755 /var/www/aromatherapy-store
sudo chmod -R 775 /var/www/aromatherapy-store/storage
sudo chmod -R 775 /var/www/aromatherapy-store/bootstrap/cache
```

5. Configure PHP
```bash
sudo nano /etc/php/8.2/apache2/php.ini
```

Update the following settings:
```ini
memory_limit = 256M
upload_max_filesize = 64M
post_max_size = 64M
max_execution_time = 300
```

6. Restart Apache
```bash
sudo systemctl restart apache2
``` 