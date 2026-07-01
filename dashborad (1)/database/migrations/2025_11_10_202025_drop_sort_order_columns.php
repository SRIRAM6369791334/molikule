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
        // Drop sort_order columns from all tables that have them
        
        // Product variants table
        if (Schema::hasColumn('product_variants', 'sort_order')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
        
        // Categories table
        if (Schema::hasColumn('categories', 'sort_order')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
        
        // Brands table
        if (Schema::hasColumn('brands', 'sort_order')) {
            Schema::table('brands', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
        
        // Banners table
        if (Schema::hasColumn('banners', 'sort_order')) {
            Schema::table('banners', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
        
        // Variant attributes table
        if (Schema::hasColumn('variant_attributes', 'sort_order')) {
            Schema::table('variant_attributes', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
        
        // Variant attribute values table
        if (Schema::hasColumn('variant_attribute_values', 'sort_order')) {
            Schema::table('variant_attribute_values', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration removes sort_order columns, so reversing would re-add them
        // However, since we're removing the functionality completely, we won't provide a reverse migration
    }
};
