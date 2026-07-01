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
        // Add sort_order columns back to all tables
        
        // Product variants table
        if (!Schema::hasColumn('product_variants', 'sort_order')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->integer('sort_order')->default(0)->after('is_active');
            });
        }
        
        // Categories table
        if (!Schema::hasColumn('categories', 'sort_order')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->integer('sort_order')->default(0)->after('is_active');
            });
        }
        
        // Brands table
        if (!Schema::hasColumn('brands', 'sort_order')) {
            Schema::table('brands', function (Blueprint $table) {
                $table->integer('sort_order')->default(0)->after('is_active');
            });
        }
        
        // Banners table
        if (!Schema::hasColumn('banners', 'sort_order')) {
            Schema::table('banners', function (Blueprint $table) {
                $table->integer('sort_order')->default(0)->after('is_active');
            });
        }
        
        // Variant attributes table
        if (!Schema::hasColumn('variant_attributes', 'sort_order')) {
            Schema::table('variant_attributes', function (Blueprint $table) {
                $table->integer('sort_order')->default(0)->after('is_active');
            });
        }
        
        // Variant attribute values table
        if (!Schema::hasColumn('variant_attribute_values', 'sort_order')) {
            Schema::table('variant_attribute_values', function (Blueprint $table) {
                $table->integer('sort_order')->default(0)->after('is_active');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the sort_order columns again
        if (Schema::hasColumn('product_variants', 'sort_order')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
        
        if (Schema::hasColumn('categories', 'sort_order')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
        
        if (Schema::hasColumn('brands', 'sort_order')) {
            Schema::table('brands', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
        
        if (Schema::hasColumn('banners', 'sort_order')) {
            Schema::table('banners', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
        
        if (Schema::hasColumn('variant_attributes', 'sort_order')) {
            Schema::table('variant_attributes', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
        
        if (Schema::hasColumn('variant_attribute_values', 'sort_order')) {
            Schema::table('variant_attribute_values', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
    }
};
