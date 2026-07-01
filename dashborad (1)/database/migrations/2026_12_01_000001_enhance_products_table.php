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
                $table->string('slug')->unique()->after('name')->nullable();
            }
            if (!Schema::hasColumn('products', 'short_description')) {
                $table->text('short_description')->after('description')->nullable();
            }
            if (!Schema::hasColumn('products', 'compare_price')) {
                $table->decimal('compare_price', 10, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('products', 'cost_per_item')) {
                $table->decimal('cost_per_item', 10, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('products', 'track_quantity')) {
                $table->integer('track_quantity')->default(1)->after('stock_quantity');
            }
            if (!Schema::hasColumn('products', 'continue_selling_when_out_of_stock')) {
                $table->boolean('continue_selling_when_out_of_stock')->default(0)->after('track_quantity');
            }
            if (!Schema::hasColumn('products', 'barcode')) {
                $table->string('barcode')->nullable()->after('sku');
            }
            if (!Schema::hasColumn('products', 'weight_unit')) {
                $table->string('weight_unit', 10)->default('kg')->after('weight');
            }
            if (!Schema::hasColumn('products', 'length')) {
                $table->decimal('length', 8, 2)->nullable()->after('weight');
            }
            if (!Schema::hasColumn('products', 'width')) {
                $table->decimal('width', 8, 2)->nullable()->after('weight');
            }
            if (!Schema::hasColumn('products', 'height')) {
                $table->decimal('height', 8, 2)->nullable()->after('weight');
            }
            if (!Schema::hasColumn('products', 'dimension_unit') && Schema::hasColumn('products', 'dimension')) {
                $table->string('dimension_unit', 10)->default('cm')->after('dimension');
            }
            if (!Schema::hasColumn('products', 'tags')) {
                $table->json('tags')->nullable()->after('structured_data');
            }
            if (!Schema::hasColumn('products', 'custom_fields')) {
                $table->json('custom_fields')->nullable()->after('structured_data');
            }
        });
        
        $this->addIndexIfNotExists('products', ['active', 'stock_quantity']);
        $this->addIndexIfNotExists('products', ['category_id', 'active']);
        $this->addIndexIfNotExists('products', ['brand_id', 'active']);
        $this->addIndexIfNotExists('products', ['slug']);
        $this->addIndexIfNotExists('products', ['price', 'active']);
        $this->addIndexIfNotExists('products', ['created_at']);
    }

    private function addIndexIfNotExists(string $table, $columns): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        try {
            Schema::table($table, function (Blueprint $blueprint) use ($columns) {
                $blueprint->index($columns);
            });
        } catch (\Exception $e) {
            if (!str_contains($e->getMessage(), 'Duplicate key name') && !str_contains($e->getMessage(), 'already exists')) {
            }
        }
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['active', 'stock_quantity']);
            $table->dropIndex(['category_id', 'active']);
            $table->dropIndex(['brand_id', 'active']);
            $table->dropIndex('slug');
            $table->dropIndex(['price', 'active']);
            $table->dropIndex('created_at');
            
            $table->dropColumn([
                'slug', 'short_description', 'compare_price', 'cost_per_item',
                'track_quantity', 'continue_selling_when_out_of_stock', 'barcode',
                'weight_unit', 'length', 'width', 'height', 'dimension_unit',
                'tags', 'custom_fields'
            ]);
        });
    }
};