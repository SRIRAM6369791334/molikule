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
        // Add indexes to product_reviews foreign keys
        Schema::table('product_reviews', function (Blueprint $table) {
            try {
                $table->index('product_id', 'product_reviews_product_id_idx');
            } catch (\Exception $e) {
                // Index may already exist
            }
            try {
                $table->index('user_id', 'product_reviews_user_id_idx');
            } catch (\Exception $e) {
                // Index may already exist
            }
        });

        // Add indexes to product_images foreign keys
        Schema::table('product_images', function (Blueprint $table) {
            try {
                $table->index('product_id', 'product_images_product_id_idx');
            } catch (\Exception $e) {
                // Index may already exist
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            try {
                $table->dropIndex('product_reviews_product_id_idx');
            } catch (\Exception $e) {
                // Index may not exist
            }
            try {
                $table->dropIndex('product_reviews_user_id_idx');
            } catch (\Exception $e) {
                // Index may not exist
            }
        });

        Schema::table('product_images', function (Blueprint $table) {
            try {
                $table->dropIndex('product_images_product_id_idx');
            } catch (\Exception $e) {
                // Index may not exist
            }
        });
    }
};
