<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - SAFE CLEANUP OF IDENTIFIED UNUSED FIELDS
     * Based on analysis of ProductService, BannerService, and application usage
     */
    public function up(): void
    {
        // ========================================
        // PHASE 1: HIGH CONFIDENCE CLEANUP (LOW RISK)
        // ========================================

        // 1. Clean banners table - remove description field (migration exists but may not have been applied consistently)
        if (Schema::hasTable('banners')) {
            // Check if description column exists before trying to remove it
            if (Schema::hasColumn('banners', 'description')) {
                Schema::table('banners', function (Blueprint $table) {
                    $table->dropColumn('description');
                });
                $this->logCleanup('banners', 'description', 'Removed unused description field');
            }
        }

        // 2. Clean products table - remove SEO fields that are not used in ProductService
        if (Schema::hasTable('products')) {
            $seoFields = [
                'canonical_url', 'structured_data', 'robots_index', 'robots_follow',
                'priority', 'change_frequency'
            ];

            $fieldsToRemove = [];
            foreach ($seoFields as $field) {
                if (Schema::hasColumn('products', $field)) {
                    $fieldsToRemove[] = $field;
                }
            }

            if (!empty($fieldsToRemove)) {
                Schema::table('products', function (Blueprint $table) use ($fieldsToRemove) {
                    $table->dropColumn($fieldsToRemove);
                });
                $this->logCleanup('products', implode(', ', $fieldsToRemove), 'Removed unused SEO fields');
            }

            // Remove social media meta fields (likely not used in admin dashboard)
            $socialFields = ['og_title', 'og_description', 'og_image', 'twitter_title', 'twitter_description', 'twitter_image'];
            $socialFieldsToRemove = [];
            foreach ($socialFields as $field) {
                if (Schema::hasColumn('products', $field)) {
                    $socialFieldsToRemove[] = $field;
                }
            }

            if (!empty($socialFieldsToRemove)) {
                Schema::table('products', function (Blueprint $table) use ($socialFieldsToRemove) {
                    $table->dropColumn($socialFieldsToRemove);
                });
                $this->logCleanup('products', implode(', ', $socialFieldsToRemove), 'Removed social media meta fields');
            }
        }

        // 3. Clean categories table - standardize description field removal
        if (Schema::hasTable('categories')) {
            if (Schema::hasColumn('categories', 'description') && Schema::hasColumn('categories', 'icon_class')) {
                Schema::table('categories', function (Blueprint $table) {
                    $table->dropColumn(['description', 'icon_class']);
                });
                $this->logCleanup('categories', 'description, icon_class', 'Removed unused category fields');
            }
        }

        // 4. Clean brands table - remove description if it exists
        if (Schema::hasTable('brands')) {
            if (Schema::hasColumn('brands', 'description')) {
                Schema::table('brands', function (Blueprint $table) {
                    $table->dropColumn('description');
                });
                $this->logCleanup('brands', 'description', 'Removed unused brand description');
            }
        }
    }

    /**
     * Reverse the migrations - RESTORE FIELDS (for rollback if needed)
     */
    public function down(): void
    {
        // ========================================
        // ROLLBACK: Restore removed fields (if rollback is needed)
        // ========================================

        // Note: Since we can't know the exact original field definitions,
        // this rollback provides basic field restoration. You may need manual adjustment.

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                // Restore SEO fields if they don't exist
                if (!Schema::hasColumn('products', 'canonical_url')) {
                    $table->string('canonical_url')->nullable()->after('meta_keywords');
                }
                if (!Schema::hasColumn('products', 'structured_data')) {
                    $table->json('structured_data')->nullable()->after('canonical_url');
                }
                if (!Schema::hasColumn('products', 'robots_index')) {
                    $table->boolean('robots_index')->default(true)->after('structured_data');
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

                // Restore social media fields
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
            });
        }

        // Restore banners description
        if (Schema::hasTable('banners') && !Schema::hasColumn('banners', 'description')) {
            Schema::table('banners', function (Blueprint $table) {
                $table->text('description')->nullable()->after('title');
            });
        }

        // Note: Categories and brands description restoration would require
        // knowledge of original field structure. Handle manually if needed.
    }

    /**
     * Log cleanup actions for tracking
     */
    private function logCleanup($table, $fields, $reason)
    {
        // Create a cleanup log for tracking what was removed
        $logFile = storage_path('logs/database_cleanup.log');
        $logEntry = sprintf(
            "[%s] CLEANUP: Removed from %s - Fields: %s | Reason: %s\n",
            now()->format('Y-m-d H:i:s'),
            $table,
            $fields,
            $reason
        );

        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }
};
