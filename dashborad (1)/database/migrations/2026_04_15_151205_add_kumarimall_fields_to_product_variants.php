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
        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('sku')->nullable()->after('product_id');
            $table->string('barcode')->nullable()->after('sku');
            $table->integer('low_stock')->default(5)->after('stock_quantity');
            $table->integer('unit_id')->nullable()->after('variant_unit');
            $table->decimal('offer_price', 15, 2)->nullable()->after('compare_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['sku', 'barcode', 'low_stock', 'unit_id', 'offer_price']);
        });
    }
};
