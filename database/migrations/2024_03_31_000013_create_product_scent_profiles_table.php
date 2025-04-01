<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_scent_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('scent_profile_id')->constrained()->onDelete('cascade');
            $table->integer('intensity')->unsigned()->default(5);
            $table->timestamps();
            
            $table->unique(['product_id', 'scent_profile_id']);
            $table->index(['product_id', 'scent_profile_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_scent_profiles');
    }
}; 