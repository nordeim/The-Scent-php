<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->tinyInteger('id')->unsigned()->primary();
            $table->string('name', 20)->unique();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_roles');
    }
}; 