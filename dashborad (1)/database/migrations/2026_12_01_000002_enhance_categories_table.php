<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Categories table - Add enhanced fields with existence checks
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'slug')) {
                $table->string('slug', 255)->unique()->nullable()->after('category_name');
            }
            if (!Schema::hasColumn('categories', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('description');
            }
            if (!Schema::hasColumn('categories', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('categories', 'meta_keywords')) {
                $table->text('meta_keywords')->nullable()->after('meta_description');
            }
            if (!Schema::hasColumn('categories', 'canonical_url')) {
                $table->string('canonical_url')->nullable()->after('meta_keywords');
            }
            if (!Schema::hasColumn('categories', 'content')) {
                $table->text('content')->nullable()->after('description');
            }
            if (!Schema::hasColumn('categories', 'icon')) {
                $table->string('icon')->nullable()->after('image');
            }
            if (!Schema::hasColumn('categories', 'banner_image')) {
                $table->string('banner_image')->nullable()->after('icon');
            }
            if (!Schema::hasColumn('categories', 'custom_fields')) {
                $table->json('custom_fields')->nullable()->after('banner_image');
            }
            if (!Schema::hasColumn('categories', 'product_count')) {
                $table->integer('product_count')->default(0)->after('sort_order');
            }
            if (!Schema::hasColumn('categories', 'is_featured')) {
                $table->boolean('is_featured')->default(0)->after('is_active');
            }
            if (!Schema::hasColumn('categories', 'view_count')) {
                $table->integer('view_count')->default(0)->after('is_featured');
            }
            
        });
        
        $this->addIndexIfNotExists('categories', ['parent_id', 'is_active'], 'categories_parent_active_index');
        $this->addIndexIfNotExists('categories', ['is_active', 'sort_order'], 'categories_active_sort_index');
        $this->addIndexIfNotExists('categories', ['slug'], 'categories_slug_index');
        $this->addIndexIfNotExists('categories', ['is_featured'], 'categories_featured_index');
    }

    private function addIndexIfNotExists(string $table, $columns, string $indexName = null): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        try {
            Schema::table($table, function (Blueprint $blueprint) use ($columns, $indexName) {
                if ($indexName) {
                    $blueprint->index($columns, $indexName);
                } else {
                    $blueprint->index($columns);
                }
            });
        } catch (\Exception $e) {
            if (!str_contains($e->getMessage(), 'Duplicate key name') && !str_contains($e->getMessage(), 'already exists')) {
            }
        }
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            try {
                $table->dropIndex(['parent_id', 'is_active']);
                $table->dropIndex(['is_active', 'sort_order']);
                $table->dropIndex(['slug']);
                $table->dropIndex(['is_featured']);
            } catch (\Exception $e) {
                // Indexes might not exist, ignore error
            }
            
            $table->dropColumn([
                'slug', 'meta_title', 'meta_description', 'meta_keywords',
                'canonical_url', 'content', 'icon', 'banner_image',
                'custom_fields', 'product_count', 'is_featured', 'view_count'
            ]);
        });
    }
};