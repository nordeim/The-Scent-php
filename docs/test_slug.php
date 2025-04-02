<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Get the first product
    $product = App\Models\Product::find(1);
    echo "First product slug: " . $product->slug . "\n";

    // Create a second product with the same name
    $product2 = new App\Models\Product();
    $product2->name = "Test Essential Oil";
    $product2->price = 39.99;
    $product2->description = "Another test oil";
    $product2->short_description = "Test oil 2";
    $product2->stock = 50;
    $product2->sku = "TEO-" . rand(1000, 9999);
    $product2->product_type = "essential_oil";
    $product2->category_id = 1;
    $product2->save();
    
    echo "Second product slug: " . $product2->slug . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}