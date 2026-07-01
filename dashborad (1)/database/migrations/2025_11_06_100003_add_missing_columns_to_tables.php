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
        // Add missing columns to categories table
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'description')) {
                $table->text('description')->nullable()->after('category_name');
            }
            if (!Schema::hasColumn('categories', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('image_url');
            }
            if (!Schema::hasColumn('categories', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->nullable()->after('category_id');
                $table->foreign('parent_id')
                      ->references('category_id')
                      ->on('categories')
                      ->onDelete('set null');
            }
        });

        // Add missing columns to brands table
        Schema::table('brands', function (Blueprint $table) {
            if (!Schema::hasColumn('brands', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('is_active');
            }
        });

        // Add missing columns to pincodes table
        Schema::table('pincodes', function (Blueprint $table) {
            if (!Schema::hasColumn('pincodes', 'country')) {
                $table->string('country', 100)->default('India')->after('state');
            }
            if (!Schema::hasColumn('pincodes', 'cod_charge')) {
                $table->decimal('cod_charge', 8, 2)->default(120.00)->after('country');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove columns from categories table
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'parent_id')) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            }
            if (Schema::hasColumn('categories', 'sort_order')) {
                $table->dropColumn('sort_order');
            }
            if (Schema::hasColumn('categories', 'description')) {
                $table->dropColumn('description');
            }
        });

        // Remove columns from brands table
        Schema::table('brands', function (Blueprint $table) {
            if (Schema::hasColumn('brands', 'sort_order')) {
                $table->dropColumn('sort_order');
            }
        });

        // Remove columns from pincodes table
        Schema::table('pincodes', function (Blueprint $table) {
            if (Schema::hasColumn('pincodes', 'cod_charge')) {
                $table->dropColumn('cod_charge');
            }
            if (Schema::hasColumn('pincodes', 'country')) {
                $table->dropColumn('country');
            }
        });
    }
};

