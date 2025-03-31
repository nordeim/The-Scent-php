<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2);
            $table->text('description');
            $table->string('short_description', 255)->nullable();
            $table->string('image_url');
            $table->boolean('featured')->default(false);
            $table->integer('review_count')->unsigned()->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('stock')->unsigned()->default(100);
            $table->string('sku', 50)->unique();
            
            // Aromatherapy specific fields
            $table->enum('product_type', ['essential_oil', 'soap']);
            $table->string('origin_country', 100)->nullable();
            $table->string('extraction_method', 100)->nullable();
            $table->string('botanical_name', 255)->nullable();
            $table->text('safety_notes')->nullable();
            $table->text('usage_instructions')->nullable();
            $table->string('shelf_life', 50)->nullable();
            $table->boolean('is_customizable')->default(false);
            
            $table->timestamps();
            
            $table->index('featured');
            $table->index('category_id');
            $table->fullText(['name', 'short_description', 'description']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}; 