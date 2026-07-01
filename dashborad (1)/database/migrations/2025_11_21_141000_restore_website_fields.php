<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * EMERGENCY RESTORATION: Restore database fields that website actually uses.
     *
     * Critical discovery: Website Product model includes 30+ enhanced fields for:
     * - SEO (meta_title, meta_description, etc.)
     * - Social media (og_title, twitter_title, etc.)
     * - Product details (weight, dimensions, warranty, etc.)
     * - Structured data generation
     *
     * Admin only uses ~15 fields, but website uses ~35 fields.
     */
    public function up(): void
    {
        // ========================================
        // PRODUCTS TABLE - RESTORE fields website depends on
        // ========================================
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                // Core product info that website uses
                if (!Schema::hasColumn('products', 'slug')) {
                    $table->string('slug')->nullable()->after('name');
                }
                if (!Schema::hasColumn('products', 'description')) {
                    $table->longText('description')->nullable()->after('slug');
                }
                if (!Schema::hasColumn('products', 'short_description')) {
                    $table->text('short_description')->nullable()->after('description');
                }

                // Price fields website displays
                if (!Schema::hasColumn('products', 'cost_per_item')) {
                    $table->decimal('cost_per_item', 10, 2)->nullable()->after('short_description');
                }
                if (!Schema::hasColumn('products', 'compare_price')) {
                    $table->decimal('compare_price', 10, 2)->nullable()->after('cost_per_item');
                }
                if (!Schema::hasColumn('products', 'original_price')) {
                    $table->decimal('original_price', 10, 2)->nullable()->after('compare_price');
                }

                // Identification that website shows
                if (!Schema::hasColumn('products', 'sku')) {
                    $table->string('sku')->nullable()->after('original_price');
                }
                if (!Schema::hasColumn('products', 'part_number')) {
                    $table->string('part_number')->nullable()->after('sku');
                }

                // Physical product details website displays
                if (!Schema::hasColumn('products', 'weight')) {
                    $table->decimal('weight', 10, 2)->nullable()->after('part_number');
                }
                if (!Schema::hasColumn('products', 'length')) {
                    $table->decimal('length', 10, 2)->nullable()->after('weight');
                }
                if (!Schema::hasColumn('products', 'width')) {
                    $table->decimal('width', 10, 2)->nullable()->after('length');
                }
                if (!Schema::hasColumn('products', 'height')) {
                    $table->decimal('height', 10, 2)->nullable()->after('width');
                }
                if (!Schema::hasColumn('products', 'dimension')) {
                    $table->string('dimension')->nullable()->after('height');
                }

                // Additional details website shows
                if (!Schema::hasColumn('products', 'made_in')) {
                    $table->string('made_in')->nullable()->after('dimension');
                }
                if (!Schema::hasColumn('products', 'warranty')) {
                    $table->string('warranty')->nullable()->after('made_in');
                }
                if (!Schema::hasColumn('products', 'supported_brands')) {
                    $table->text('supported_brands')->nullable()->after('warranty');
                }
                if (!Schema::hasColumn('products', 'seller')) {
                    $table->string('seller')->nullable()->after('supported_brands');
                }

                // Metadata fields
                if (!Schema::hasColumn('products', 'tags')) {
                    $table->json('tags')->nullable()->after('seller');
                }
                if (!Schema::hasColumn('products', 'custom_fields')) {
                    $table->json('custom_fields')->nullable()->after('tags');
                }
                if (!Schema::hasColumn('products', 'condition')) {
                    $table->string('condition')->nullable()->after('custom_fields');
                }

                // SEO fields website generates and uses
                if (!Schema::hasColumn('products', 'meta_title')) {
                    $table->string('meta_title')->nullable()->after('condition');
                }
                if (!Schema::hasColumn('products', 'meta_description')) {
                    $table->text('meta_description')->nullable()->after('meta_title');
                }
                if (!Schema::hasColumn('products', 'meta_keywords')) {
                    $table->text('meta_keywords')->nullable()->after('meta_description');
                }
                if (!Schema::hasColumn('products', 'canonical_url')) {
                    $table->string('canonical_url')->nullable()->after('meta_keywords');
                }
                if (!Schema::hasColumn('products', 'robots_index')) {
                    $table->boolean('robots_index')->default(true)->after('canonical_url');
                }
                if (!Schema::hasColumn('products', 'robots_follow')) {
                    $table->boolean('robots_follow')->default(true)->after('robots_index');
                }
                if (!Schema::hasColumn('products', 'priority')) {
                    $table->decimal('priority', 2, 1)->default(0.5)->after('robots_follow');
                }
                if (!Schema::hasColumn('products', 'change_frequency')) {
                    $table->enum('change_frequency', ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'])
                          ->default('weekly')->after('priority');
                }

                // Social media fields website generates
                if (!Schema::hasColumn('products', 'og_title')) {
                    $table->string('og_title')->nullable()->after('change_frequency');
                }
                if (!Schema::hasColumn('products', 'og_description')) {
                    $table->text('og_description')->nullable()->after('og_title');
                }
                if (!Schema::hasColumn('products', 'og_image')) {
                    $table->string('og_image')->nullable()->after('og_description');
                }
                if (!Schema::hasColumn('products', 'twitter_title')) {
                    $table->string('twitter_title')->nullable()->after('og_image');
                }
                if (!Schema::hasColumn('products', 'twitter_description')) {
                    $table->text('twitter_description')->nullable()->after('twitter_title');
                }
                if (!Schema::hasColumn('products', 'twitter_image')) {
                    $table->string('twitter_image')->nullable()->after('twitter_description');
                }

                // Structured data website generates
                if (!Schema::hasColumn('products', 'structured_data')) {
                    $table->json('structured_data')->nullable()->after('twitter_image');
                }
            });
        }

        // ========================================
        // CATEGORIES TABLE - Restore SEO/content fields website uses
        // ========================================
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                if (!Schema::hasColumn('categories', 'description')) {
                    $table->text('description')->nullable();
                }
                if (!Schema::hasColumn('categories', 'category_content')) {
                    $table->longText('category_content')->nullable();
                }
                if (!Schema::hasColumn('categories', 'category_sidebar_content')) {
                    $table->text('category_sidebar_content')->nullable();
                }
                if (!Schema::hasColumn('categories', 'short_description')) {
                    $table->text('short_description')->nullable();
                }
                if (!Schema::hasColumn('categories', 'content')) {
                    $table->text('content')->nullable();
                }
                if (!Schema::hasColumn('categories', 'meta_title')) {
                    $table->string('meta_title', 60)->nullable();
                }
                if (!Schema::hasColumn('categories', 'meta_description')) {
                    $table->text('meta_description', 160)->nullable();
                }
                if (!Schema::hasColumn('categories', 'meta_keywords')) {
                    $table->text('meta_keywords')->nullable();
                }
            });
        }

        $this->logCleanup('RESTORATION', 'website_essential_fields',
            'RESTORED essential fields that website depends on for SEO and display');
    }

    public function down(): void
    {
        // This migration is for restoration - no down needed for removal
        $this->logCleanup('RESTORATION', 'rollback_not_needed', 'Restoration migration - keep fields');
    }

    private function logCleanup($table, $fields, $reason)
    {
        $logFile = storage_path('logs/database_cleanup.log');
        $logEntry = sprintf(
            "[%s] EMERGENCY_RESTORATION: %s - %s | Reason: %s\n",
            now()->format('Y-m-d H:i:s'),
            $table,
            $fields,
            $reason
        );
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }
};
