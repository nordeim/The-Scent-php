<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->timestamps();

                $table->index('key', 'idx_settings_key');
            });

            // Insert default settings
            $defaultSettings = [
                ['key' => 'site_name', 'value' => 'Aromatherapy Store'],
                ['key' => 'site_description', 'value' => 'Your one-stop shop for aromatherapy products'],
                ['key' => 'contact_email', 'value' => 'contact@aromatherapystore.com'],
                ['key' => 'shipping_cost', 'value' => '5.00'],
                ['key' => 'free_shipping_threshold', 'value' => '50.00'],
                ['key' => 'tax_rate', 'value' => '0.10'],
                ['key' => 'currency', 'value' => 'USD'],
                ['key' => 'currency_symbol', 'value' => '$'],
                ['key' => 'maintenance_mode', 'value' => 'false'],
                ['key' => 'allow_registration', 'value' => 'true'],
                ['key' => 'allow_reviews', 'value' => 'true'],
                ['key' => 'require_review_approval', 'value' => 'true'],
                ['key' => 'items_per_page', 'value' => '12'],
                ['key' => 'featured_products_count', 'value' => '4'],
                ['key' => 'featured_articles_count', 'value' => '3'],
                ['key' => 'popular_products_count', 'value' => '8'],
                ['key' => 'popular_tags_count', 'value' => '10']
            ];

            DB::table('settings')->insert($defaultSettings);
        }
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};