<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->string('featured_image')->nullable();
            $table->string('meta_description', 255)->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->enum('category', ['education', 'news', 'tips']);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('author_id')->constrained('users');
            $table->timestamps();
            
            $table->index(['category', 'is_published']);
            $table->index('published_at');
            $table->fullText(['title', 'content']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('articles');
    }
}; 