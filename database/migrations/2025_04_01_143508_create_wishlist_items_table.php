<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('wishlist_items')) {
            Schema::create('wishlist_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->timestamps();

                $table->unique(['user_id', 'product_id'], 'unique_user_product');
                $table->index('user_id', 'idx_wishlist_items_user');
                $table->index('product_id', 'idx_wishlist_items_product');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('wishlist_items');
    }
};