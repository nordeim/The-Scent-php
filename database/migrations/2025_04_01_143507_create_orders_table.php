<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
                $table->decimal('total_amount', 10, 2);
                $table->text('shipping_address');
                $table->text('billing_address');
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index('user_id', 'idx_orders_user');
                $table->index('status', 'idx_orders_status');
                $table->index('created_at', 'idx_orders_created');
                $table->index('updated_at', 'idx_orders_updated');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};