<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('scent_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('icon_class', 50)->nullable();
            $table->boolean('featured')->default(false);
            $table->timestamps();
            
            $table->index('featured');
        });
    }

    public function down()
    {
        Schema::dropIfExists('scent_profiles');
    }
}; 