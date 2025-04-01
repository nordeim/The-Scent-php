<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->integer('quantity');
                $table->decimal('price', 10, 2);
                $table->timestamps();

                $table->index('order_id', 'idx_order_items_order');
                $table->index('product_id', 'idx_order_items_product');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};