<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (!Schema::hasColumn('product_variants', 'variant_unit')) {
                $table->string('variant_unit', 20)->nullable()->after('value')
                      ->comment('Unit for the variant value e.g. ml, liter, g, kg, pcs, oz');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (Schema::hasColumn('product_variants', 'variant_unit')) {
                $table->dropColumn('variant_unit');
            }
        });
    }
};
