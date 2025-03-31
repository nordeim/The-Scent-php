<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_moods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('mood_id')->constrained()->onDelete('cascade');
            $table->integer('effectiveness')->unsigned()->default(5);
            $table->timestamps();
            
            $table->unique(['product_id', 'mood_id']);
            $table->index(['product_id', 'mood_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_moods');
    }
}; 