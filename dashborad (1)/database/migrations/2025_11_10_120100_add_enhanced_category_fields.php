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
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'slug')) {
                $table->string('slug', 255)->nullable()->after('category_name');
            }
            if (!Schema::hasColumn('categories', 'short_description')) {
                $table->text('short_description')->nullable()->after('description');
            }
            if (!Schema::hasColumn('categories', 'meta_title')) {
                $table->string('meta_title', 60)->nullable()->after('description');
            }
            if (!Schema::hasColumn('categories', 'meta_description')) {
                $table->text('meta_description', 160)->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('categories', 'meta_keywords')) {
                $table->text('meta_keywords')->nullable()->after('meta_description');
            }
            if (!Schema::hasColumn('categories', 'breadcrumb_title')) {
                $table->string('breadcrumb_title', 255)->nullable()->after('category_name');
            }
            if (!Schema::hasColumn('categories', 'parent_breadcrumb_title')) {
                $table->string('parent_breadcrumb_title', 255)->nullable()->after('breadcrumb_title');
            }
            if (!Schema::hasColumn('categories', 'category_banner') && Schema::hasColumn('categories', 'image_url')) {
                $table->string('category_banner', 255)->nullable()->after('image_url');
            }
            if (!Schema::hasColumn('categories', 'category_tags')) {
                $table->text('category_tags')->nullable()->after('category_banner');
            }
            if (!Schema::hasColumn('categories', 'is_featured')) {
                $table->tinyInteger('is_featured')->default(0)->after('is_active');
            }
            if (!Schema::hasColumn('categories', 'show_on_homepage')) {
                $table->tinyInteger('show_on_homepage')->default(0)->after('is_featured');
            }
            if (!Schema::hasColumn('categories', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0)->after('is_active');
            }
            if (!Schema::hasColumn('categories', 'has_children')) {
                $table->boolean('has_children')->default(false)->after('sort_order');
            }
            if (!Schema::hasColumn('categories', 'product_count')) {
                $table->unsignedInteger('product_count')->default(0)->after('has_children');
            }
            if (!Schema::hasColumn('categories', 'view_count')) {
                $table->unsignedInteger('view_count')->default(0)->after('product_count');
            }
            if (!Schema::hasColumn('categories', 'category_content')) {
                $table->longText('category_content')->nullable()->after('description');
            }
            if (!Schema::hasColumn('categories', 'category_sidebar_content')) {
                $table->text('category_sidebar_content')->nullable()->after('category_content');
            }
            if (!Schema::hasColumn('categories', 'category_video_url')) {
                $table->string('category_video_url', 500)->nullable()->after('category_sidebar_content');
            }
            if (!Schema::hasColumn('categories', 'og_title')) {
                $table->text('og_title')->nullable()->after('meta_keywords');
            }
            if (!Schema::hasColumn('categories', 'og_description')) {
                $table->text('og_description')->nullable()->after('og_title');
            }
            if (!Schema::hasColumn('categories', 'og_image')) {
                $table->string('og_image', 255)->nullable()->after('og_description');
            }
            if (!Schema::hasColumn('categories', 'og_type')) {
                $table->string('og_type', 50)->default('website')->after('og_image');
            }
            if (!Schema::hasColumn('categories', 'schema_markup')) {
                $table->text('schema_markup')->nullable()->after('og_type');
            }
            if (!Schema::hasColumn('categories', 'url_structure') && Schema::hasColumn('categories', 'slug')) {
                $table->string('url_structure', 500)->nullable()->after('slug');
            }
            if (!Schema::hasColumn('categories', 'canonical_url')) {
                $table->string('canonical_url', 500)->nullable()->after('url_structure');
            }
            if (!Schema::hasColumn('categories', 'index_status')) {
                $table->enum('index_status', ['index', 'noindex', 'follow', 'nofollow'])->default('index')->after('canonical_url');
            }
            if (!Schema::hasColumn('categories', 'change_frequency')) {
                $table->string('change_frequency', 20)->default('weekly')->after('index_status');
            }
            if (!Schema::hasColumn('categories', 'last_modified')) {
                $table->timestamp('last_modified')->nullable()->after('change_frequency');
            }
            if (!Schema::hasColumn('categories', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('last_modified');
            }
        });
        
        $this->addIndexIfNotExists('categories', ['slug'], 'categories_slug_unique', true);
        $this->addIndexIfNotExists('categories', ['parent_id'], 'categories_parent_id_index');
        $this->addIndexIfNotExists('categories', ['sort_order'], 'categories_sort_order_index');
        $this->addIndexIfNotExists('categories', ['is_featured'], 'categories_is_featured_index');
        $this->addIndexIfNotExists('categories', ['is_active'], 'categories_is_active_index');
        $this->addIndexIfNotExists('categories', ['product_count'], 'categories_product_count_index');
        $this->addIndexIfNotExists('categories', ['view_count'], 'categories_view_count_index');
        $this->addIndexIfNotExists('categories', ['show_on_homepage'], 'categories_show_on_homepage_index');
        $this->addIndexIfNotExists('categories', ['has_children'], 'categories_has_children_index');
    }

    private function addIndexIfNotExists(string $table, $columns, string $indexName = null, bool $unique = false): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        try {
            Schema::table($table, function (Blueprint $blueprint) use ($columns, $indexName, $unique) {
                if ($unique) {
                    if ($indexName) {
                        $blueprint->unique($columns, $indexName);
                    } else {
                        $blueprint->unique($columns);
                    }
                } else {
                    if ($indexName) {
                        $blueprint->index($columns, $indexName);
                    } else {
                        $blueprint->index($columns);
                    }
                }
            });
        } catch (\Exception $e) {
            if (!str_contains($e->getMessage(), 'Duplicate key name') && !str_contains($e->getMessage(), 'already exists')) {
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['parent_id']);
            
            // Drop indexes
            $table->dropUnique('categories_slug_unique');
            $table->dropIndex('categories_parent_id_index');
            $table->dropIndex('categories_sort_order_index');
            $table->dropIndex('categories_is_featured_index');
            $table->dropIndex('categories_active_index');
            $table->dropIndex('categories_product_count_index');
            $table->dropIndex('categories_view_count_index');
            $table->dropIndex('categories_show_on_homepage_index');
            $table->dropIndex('categories_has_children_index');
            
            // Drop columns
            $table->dropColumn([
                'slug',
                'short_description',
                'meta_title',
                'meta_description',
                'meta_keywords',
                'breadcrumb_title',
                'parent_breadcrumb_title',
                'category_banner',
                'category_tags',
                'is_featured',
                'show_on_homepage',
                'sort_order',
                'has_children',
                'product_count',
                'view_count',
                'category_content',
                'category_sidebar_content',
                'category_video_url',
                'og_title',
                'og_description',
                'og_image',
                'og_type',
                'schema_markup',
                'url_structure',
                'canonical_url',
                'index_status',
                'change_frequency',
                'last_modified',
                'created_by'
            ]);
        });
    }
};