<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('product_reviews')) {
            Schema::create('product_reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->integer('rating')->checkConstraint('rating >= 1 AND rating <= 5');
                $table->text('comment')->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'product_id'], 'unique_user_product');
                $table->index('user_id', 'idx_product_reviews_user');
                $table->index('product_id', 'idx_product_reviews_product');
                $table->index('rating', 'idx_product_reviews_rating');
                $table->index('created_at', 'idx_product_reviews_created');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('product_reviews');
    }
};