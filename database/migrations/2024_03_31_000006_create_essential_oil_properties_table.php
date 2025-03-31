<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('essential_oil_properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('property_name', 100);
            $table->text('property_value');
            $table->timestamps();
            
            $table->index(['product_id', 'property_name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('essential_oil_properties');
    }
}; 