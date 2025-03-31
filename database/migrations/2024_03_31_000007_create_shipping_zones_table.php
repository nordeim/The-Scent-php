<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->decimal('base_rate', 10, 2);
            $table->text('countries');
            $table->string('estimated_days', 50);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipping_zones');
    }
}; 