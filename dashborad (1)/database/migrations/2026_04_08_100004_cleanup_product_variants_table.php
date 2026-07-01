<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop foreign keys first (ignore errors if they don't exist)
        foreach (['brand_id', 'category_id', 'subcategory_id'] as $fk) {
            try {
                Schema::table('product_variants', function (Blueprint $table) use ($fk) {
                    $table->dropForeign(['product_variants_' . $fk . '_foreign']);
                });
            } catch (\Exception $e) {}
            try {
                Schema::table('product_variants', function (Blueprint $table) use ($fk) {
                    $table->dropForeign([$fk]);
                });
            } catch (\Exception $e) {}
        }

        // Drop each column individually
        $toDrop = ['brand_id', 'category_id', 'subcategory_id', 'low_stock_threshold', 'variant'];
        foreach ($toDrop as $col) {
            if (Schema::hasColumn('product_variants', $col)) {
                Schema::table('product_variants', function (Blueprint $table) use ($col) {
                    $table->dropColumn($col);
                });
            }
        }

        // Add compare_price if missing
        if (!Schema::hasColumn('product_variants', 'compare_price')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->decimal('compare_price', 10, 2)->nullable()->after('mrp_price');
            });
        }
    }

    public function down(): void {}
};
