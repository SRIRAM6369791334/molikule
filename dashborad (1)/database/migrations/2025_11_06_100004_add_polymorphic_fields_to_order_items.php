<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Add polymorphic fields for flexible item types
            if (!Schema::hasColumn('order_items', 'itemable_type')) {
                $table->string('itemable_type')->nullable()->after('order_id');
            }
            if (!Schema::hasColumn('order_items', 'itemable_id')) {
                $table->unsignedBigInteger('itemable_id')->nullable()->after('itemable_type');
            }
            
            // Add additional fields from model
            if (!Schema::hasColumn('order_items', 'item_name')) {
                $table->string('item_name')->nullable()->after('itemable_id');
            }
            if (!Schema::hasColumn('order_items', 'sku')) {
                $table->string('sku')->nullable()->after('item_name');
            }
            if (!Schema::hasColumn('order_items', 'unit_price')) {
                $table->decimal('unit_price', 10, 2)->nullable()->after('sku');
            }
            if (!Schema::hasColumn('order_items', 'item_options')) {
                $table->json('item_options')->nullable()->after('quantity');
            }
        });

        // Add indexes for polymorphic relationship (check if not exists)
        $sm = Schema::getConnection()->getDoctrineSchemaManager();
        $indexesFound = $sm->listTableIndexes('order_items');
        $indexExists = false;
        
        foreach ($indexesFound as $index) {
            if (in_array('itemable_type', $index->getColumns()) && in_array('itemable_id', $index->getColumns())) {
                $indexExists = true;
                break;
            }
        }
        
        if (!$indexExists) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->index(['itemable_type', 'itemable_id']);
            });
        }

        // Migrate existing data to new structure
        if (Schema::hasColumn('order_items', 'product_id') && 
            Schema::hasColumn('order_items', 'product_name') && 
            Schema::hasColumn('order_items', 'product_price')) {
            DB::statement('
                UPDATE order_items 
                SET 
                    itemable_type = "App\\\\Models\\\\Product",
                    itemable_id = product_id,
                    item_name = product_name,
                    unit_price = product_price
                WHERE itemable_type IS NULL
            ');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['itemable_type', 'itemable_id']);
            
            if (Schema::hasColumn('order_items', 'item_options')) {
                $table->dropColumn('item_options');
            }
            if (Schema::hasColumn('order_items', 'unit_price')) {
                $table->dropColumn('unit_price');
            }
            if (Schema::hasColumn('order_items', 'sku')) {
                $table->dropColumn('sku');
            }
            if (Schema::hasColumn('order_items', 'item_name')) {
                $table->dropColumn('item_name');
            }
            if (Schema::hasColumn('order_items', 'itemable_id')) {
                $table->dropColumn('itemable_id');
            }
            if (Schema::hasColumn('order_items', 'itemable_type')) {
                $table->dropColumn('itemable_type');
            }
        });
    }
};

