<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $toDrop = [
                'low_stock_threshold', 'track_quantity', 'continue_selling_when_out_of_stock',
                'barcode', 'weight', 'weight_unit', 'length', 'width', 'height', 'dimension_unit',
                'cost_per_item', 'view_count', 'average_rating', 'structured_data', 'custom_fields',
                'tags',
            ];
            foreach ($toDrop as $col) {
                if (Schema::hasColumn('products', $col)) {
                    $table->dropColumn($col);
                }
            }

            // Add badge column if missing
            if (!Schema::hasColumn('products', 'badge')) {
                $table->string('badge', 30)->nullable()->after('is_featured')
                      ->comment('Display badge: New, Sale, Popular');
            }

            // Rename 'active' to 'is_active' if needed
            // NOTE: 'active' exists, we keep it as-is if is_active doesn't exist
            // products already has 'active' column - we'll alias it in the model
        });
    }

    public function down(): void {}
};
