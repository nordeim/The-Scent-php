<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('soap_customization_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('option_name', 100);
            $table->enum('option_type', ['color', 'scent', 'size', 'shape']);
            $table->decimal('price_adjustment', 10, 2)->default(0);
            $table->timestamps();
            
            $table->index(['product_id', 'option_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('soap_customization_options');
    }
}; 