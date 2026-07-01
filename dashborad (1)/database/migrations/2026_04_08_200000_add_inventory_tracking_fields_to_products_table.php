<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Restores critical inventory tracking fields dropped in previous cleanup.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'track_quantity')) {
                $table->boolean('track_quantity')->default(1)->after('stock_quantity')
                      ->comment('Whether to track inventory for this product');
            }

            if (!Schema::hasColumn('products', 'continue_selling_when_out_of_stock')) {
                $table->boolean('continue_selling_when_out_of_stock')->default(0)->after('track_quantity')
                      ->comment('Whether to allow sales when stock is zero or less');
            }

            if (!Schema::hasColumn('products', 'low_stock_threshold')) {
                $table->integer('low_stock_threshold')->default(15)->after('continue_selling_when_out_of_stock')
                      ->comment('Product-specific low stock alert threshold');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['track_quantity', 'continue_selling_when_out_of_stock', 'low_stock_threshold']);
        });
    }
};
