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
        // First, fill in default values for existing records with null variant_value
        DB::table('product_variants')
            ->whereNull('variant_value')
            ->update(['variant_value' => 'Manual Entry']);

        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('variant_value', 255)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('variant_value', 100)->nullable()->change();
        });
    }
};
