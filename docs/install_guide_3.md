https://github.com/copilot/share/c8314216-4120-80d3-a052-fe01842941b0  
https://github.com/copilot/share/c8314216-4120-80d3-a052-fe01842941b0  
Ah, now I have the complete schema! Let's update those newly created migration files to match your schema exactly:

```php name=database/migrations/2025_04_01_143507_create_cart_items_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('cart_items')) {
            Schema::create('cart_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->integer('quantity')->default(1);
                $table->timestamps();
                
                $table->unique(['user_id', 'product_id'], 'unique_user_product');
                $table->index('user_id', 'idx_cart_items_user');
                $table->index('product_id', 'idx_cart_items_product');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
};
```

```php name=database/migrations/2025_04_01_143507_create_orders_table.php
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
```

```php name=database/migrations/2025_04_01_143507_create_order_items_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->integer('quantity');
                $table->decimal('price', 10, 2);
                $table->timestamps();

                $table->index('order_id', 'idx_order_items_order');
                $table->index('product_id', 'idx_order_items_product');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
```

```php name=database/migrations/2025_04_01_143507_create_password_reset_tokens_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
                
                $table->index('email', 'idx_email');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
```

Would you like me to continue with the remaining migration files? I can show you:
1. personal_access_tokens
2. product_reviews
3. settings
4. wishlist_items

Let me know which ones you'd like to see next!
