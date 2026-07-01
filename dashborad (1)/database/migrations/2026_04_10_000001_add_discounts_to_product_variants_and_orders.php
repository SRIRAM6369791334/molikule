<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $blueprint) {
            $blueprint->enum('discount_type', ['percentage', 'flat'])->nullable()->after('compare_price');
            $blueprint->decimal('discount_value', 10, 2)->default(0.00)->after('discount_type');
        });

        Schema::table('orders', function (Blueprint $blueprint) {
            $blueprint->decimal('discount_amount', 10, 2)->default(0.00)->after('total_amount');
            $blueprint->string('coupon_code')->nullable()->after('discount_amount');
            $blueprint->decimal('coupon_discount', 10, 2)->default(0.00)->after('coupon_code');
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['discount_type', 'discount_value']);
        });

        Schema::table('orders', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['discount_amount', 'coupon_code', 'coupon_discount']);
        });
    }
};
