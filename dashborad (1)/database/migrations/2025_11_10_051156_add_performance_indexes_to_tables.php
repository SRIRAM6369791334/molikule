<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addIndexIfNotExists('orders', 'status');
        $this->addIndexIfNotExists('orders', 'created_at');
        $this->addIndexIfNotExists('orders', 'order_number');
        $this->addIndexIfNotExists('orders', ['status', 'created_at']);
        
        $this->addIndexIfNotExists('products', 'category_id');
        $this->addIndexIfNotExists('products', 'brand_id');
        $this->addIndexIfNotExists('products', 'active');
        $this->addIndexIfNotExists('products', 'stock_quantity');
        $this->addIndexIfNotExists('products', ['active', 'stock_quantity']);
        $this->addIndexIfNotExists('products', 'created_at');
        
        $this->addIndexIfNotExists('order_items', 'order_id');
        $this->addIndexIfNotExists('order_items', ['itemable_type', 'itemable_id']);
        
        $this->addIndexIfNotExists('product_variants', 'product_id');
        $this->addIndexIfNotExists('product_variants', 'is_active');
        
        $this->addIndexIfNotExists('categories', 'is_active');
        $this->addIndexIfNotExists('brands', 'is_active');
        
        $this->addIndexIfNotExists('pincodes', 'pincode');
        $this->addIndexIfNotExists('pincodes', 'is_active');
    }

    private function addIndexIfNotExists(string $table, string|array $columns): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        try {
            Schema::table($table, function (Blueprint $blueprint) use ($columns) {
                $blueprint->index($columns);
            });
        } catch (\Exception $e) {
            if (!str_contains($e->getMessage(), 'Duplicate key name')) {
                throw $e;
            }
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['customer_email']);
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['order_number']);
            $table->dropIndex(['status', 'created_at']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropIndex(['brand_id']);
            $table->dropIndex(['active']);
            $table->dropIndex(['stock_quantity']);
            $table->dropIndex(['active', 'stock_quantity']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['itemable_type', 'itemable_id']);
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['is_active']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });

        Schema::table('pincodes', function (Blueprint $table) {
            $table->dropIndex(['pincode']);
            $table->dropIndex(['is_active']);
        });
    }
};
