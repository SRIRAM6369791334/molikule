<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add model validation constraints to order_items table
        // This ensures itemable_type can only be specific model classes
        Schema::table('order_items', function (Blueprint $table) {
            // Add CHECK constraint to enforce valid itemable types
            // Only App\Models\Product and App\Models\ProductVariant are allowed
            DB::statement("ALTER TABLE order_items ADD CONSTRAINT chk_itemable_type CHECK (itemable_type IN ('App\\\\Models\\\\Product', 'App\\\\Models\\\\ProductVariant'))");
        });

        // Also add a helper comment to document the relationship
        DB::statement("ALTER TABLE order_items COMMENT = 'Stores order line items. itemable_type: App\\\\Models\\\\Product or App\\\\Models\\\\ProductVariant'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            try {
                DB::statement("ALTER TABLE order_items DROP CONSTRAINT chk_itemable_type");
            } catch (\Exception $e) {
                // Constraint may not exist
            }
        });
    }
};
