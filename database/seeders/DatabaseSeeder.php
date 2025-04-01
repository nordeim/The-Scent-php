<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Insert default settings if they don't exist
        if (DB::table('settings')->count() === 0) {
            DB::table('settings')->insert([
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
            ]);
        }

        // Create default user roles if they don't exist
        if (DB::table('user_roles')->count() === 0) {
            DB::table('user_roles')->insert([
                ['id' => 1, 'name' => 'customer'],
                ['id' => 2, 'name' => 'admin']
            ]);
        }

        // Create default admin user if it doesn't exist
        if (!DB::table('users')->where('email', 'admin@aromatherapystore.com')->exists()) {
            DB::table('users')->insert([
                'name' => 'Admin',
                'email' => 'admin@aromatherapystore.com',
                'password' => Hash::make('change_this_password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}