<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            if (!Schema::hasColumn('cart_items', 'session_id')) {
                $table->string('session_id')->after('id');
            }
            if (!Schema::hasColumn('cart_items', 'customization_options')) {
                $table->json('customization_options')->nullable()->after('quantity');
            }
            if (!Schema::hasIndex('cart_items', 'cart_items_session_id_index')) {
                $table->index('session_id');
            }
        });
    }

    public function down()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropIndex('cart_items_session_id_index');
            $table->dropColumn(['session_id', 'customization_options']);
        });
    }
};
