<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // First, create or get a category
    $category = App\Models\Category::firstOrCreate(
        ['name' => 'Essential Oils'],
        [
            'slug' => 'essential-oils',
            'description' => 'Pure essential oils for aromatherapy'
        ]
    );

    $product = new App\Models\Product();
    $product->name = "Test Essential Oil";
    $product->price = 29.99;
    $product->description = "A test essential oil product";
    $product->short_description = "Test oil";
    $product->stock = 100;
    $product->sku = "TEO-" . rand(1000, 9999); // Generate a random SKU
    $product->product_type = "essential_oil";    // Required field from model
    $product->is_customizable = false;          // Set default value
    $product->featured = false;                 // Set default value
    $product->category_id = $category->id;      // Set the category ID
    
    $product->save();
    
    echo "Product created successfully!\n";
    echo "Product ID: " . $product->id . "\n";
    echo "Product slug: " . $product->slug . "\n";
    echo "Product SKU: " . $product->sku . "\n";
    echo "Category ID: " . $product->category_id . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}