<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'slug')) {
                $table->string('slug', 255)->unique()->nullable()->after('name');
            }
            if (!Schema::hasColumn('products', 'short_description')) {
                $table->text('short_description')->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'compare_price')) {
                $table->decimal('compare_price', 10, 2)->nullable()->after('mrp_price');
            }
            if (!Schema::hasColumn('products', 'cost_per_item')) {
                $table->decimal('cost_per_item', 10, 2)->nullable()->after('mrp_price');
            }
            if (!Schema::hasColumn('products', 'track_quantity')) {
                $table->boolean('track_quantity')->default(true)->after('stock_quantity');
            }
            if (!Schema::hasColumn('products', 'continue_selling_when_out_of_stock')) {
                $table->boolean('continue_selling_when_out_of_stock')->default(false)->after('track_quantity');
            }
            if (!Schema::hasColumn('products', 'barcode')) {
                $table->string('barcode')->nullable()->after('sku');
            }
            if (!Schema::hasColumn('products', 'weight_unit')) {
                $table->string('weight_unit', 10)->default('kg');
            }
            if (!Schema::hasColumn('products', 'length')) {
                $table->decimal('length', 8, 2)->nullable();
            }
            if (!Schema::hasColumn('products', 'width')) {
                $table->decimal('width', 8, 2)->nullable();
            }
            if (!Schema::hasColumn('products', 'height')) {
                $table->decimal('height', 8, 2)->nullable();
            }
            if (!Schema::hasColumn('products', 'dimension_unit')) {
                $table->string('dimension_unit', 10)->default('cm');
            }
            if (!Schema::hasColumn('products', 'tags')) {
                $table->json('tags')->nullable();
            }
            if (!Schema::hasColumn('products', 'custom_fields')) {
                $table->json('custom_fields')->nullable();
            }
            if (!Schema::hasColumn('products', 'view_count')) {
                $table->integer('view_count')->default(0)->after('active');
            }
        });
        
        $this->addIndexIfNotExists('products', ['active', 'stock_quantity'], 'products_active_stock_index');
        $this->addIndexIfNotExists('products', ['category_id', 'active'], 'products_category_active_index');
        $this->addIndexIfNotExists('products', ['brand_id', 'active'], 'products_brand_active_index');
        $this->addIndexIfNotExists('products', ['slug'], 'products_slug_index');
        $this->addIndexIfNotExists('products', ['mrp_price', 'active'], 'products_price_active_index');
        $this->addIndexIfNotExists('products', ['created_at'], 'products_created_at_index');
    }

    private function addIndexIfNotExists(string $table, $columns, string $indexName = null): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        try {
            Schema::table($table, function (Blueprint $blueprint) use ($columns, $indexName) {
                if ($indexName) {
                    $blueprint->index($columns, $indexName);
                } else {
                    $blueprint->index($columns);
                }
            });
        } catch (\Exception $e) {
            if (!str_contains($e->getMessage(), 'Duplicate key name') && !str_contains($e->getMessage(), 'already exists')) {
            }
        }
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            try {
                $table->dropIndex(['active', 'stock_quantity']);
                $table->dropIndex(['category_id', 'active']);
                $table->dropIndex(['brand_id', 'active']);
                $table->dropIndex(['slug']);
                $table->dropIndex(['mrp_price', 'active']);
                $table->dropIndex(['created_at']);
            } catch (\Exception $e) {
                // Indexes might not exist, ignore error
            }
            
            $table->dropColumn([
                'slug', 'short_description', 'compare_price', 'cost_per_item',
                'track_quantity', 'continue_selling_when_out_of_stock', 'barcode',
                'weight_unit', 'length', 'width', 'height', 'dimension_unit',
                'tags', 'custom_fields', 'view_count'
            ]);
        });
    }
};