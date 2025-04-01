<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('cart_items')) {
            Schema::create('cart_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->integer('quantity')->default(1);
                $table->timestamps();
                
                $table->unique(['user_id', 'product_id'], 'unique_user_product');
                $table->index('user_id', 'idx_cart_items_user');
                $table->index('product_id', 'idx_cart_items_product');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
};