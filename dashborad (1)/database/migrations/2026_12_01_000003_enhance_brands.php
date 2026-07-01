<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('brands', function (Blueprint $table) {
            // SEO enhancements
            if (!Schema::hasColumn('brands', 'slug')) {
                $table->string('slug')->unique()->after('brand_name')->nullable();
            }
            if (!Schema::hasColumn('brands', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('brand_name');
            }
            if (!Schema::hasColumn('brands', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('brands', 'meta_keywords')) {
                $table->text('meta_keywords')->nullable()->after('meta_description');
            }
            if (!Schema::hasColumn('brands', 'canonical_url')) {
                $table->string('canonical_url')->nullable()->after('meta_keywords');
            }
            
            // Enhanced brand information
            if (!Schema::hasColumn('brands', 'website_url')) {
                $table->string('website_url')->nullable()->after('logo');
            }
            if (!Schema::hasColumn('brands', 'contact_email')) {
                $table->string('contact_email')->nullable()->after('website_url');
            }
            if (!Schema::hasColumn('brands', 'contact_phone')) {
                $table->string('contact_phone')->nullable()->after('contact_email');
            }
            if (!Schema::hasColumn('brands', 'address')) {
                $table->text('address')->nullable()->after('contact_phone');
            }
            if (!Schema::hasColumn('brands', 'social_links')) {
                $table->json('social_links')->nullable()->after('address');
            }
            if (!Schema::hasColumn('brands', 'brand_colors')) {
                $table->json('brand_colors')->nullable()->after('social_links');
            }
            if (!Schema::hasColumn('brands', 'brand_story')) {
                $table->text('brand_story')->nullable()->after('brand_colors');
            }
            
            // Performance and organization
            if (!Schema::hasColumn('brands', 'product_count')) {
                $table->integer('product_count')->default(0)->after('sort_order');
            }
            if (!Schema::hasColumn('brands', 'is_featured')) {
                $table->boolean('is_featured')->default(0)->after('is_active');
            }
            if (!Schema::hasColumn('brands', 'view_count')) {
                $table->integer('view_count')->default(0)->after('is_featured');
            }
        });
        
        $this->addIndexIfNotExists('brands', ['is_active', 'sort_order']);
        $this->addIndexIfNotExists('brands', ['slug']);
        $this->addIndexIfNotExists('brands', ['is_featured']);
    }

    private function addIndexIfNotExists(string $table, $columns): void
    {
        try {
            Schema::table($table, function (Blueprint $blueprint) use ($columns) {
                $blueprint->index($columns);
            });
        } catch (\Exception $e) {}
    }

    public function down()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'sort_order']);
            $table->dropIndex('slug');
            $table->dropIndex('is_featured');
            
            $table->dropColumn([
                'slug', 'meta_title', 'meta_description', 'meta_keywords',
                'canonical_url', 'website_url', 'contact_email', 'contact_phone',
                'address', 'social_links', 'brand_colors', 'brand_story',
                'product_count', 'is_featured', 'view_count'
            ]);
        });
    }
};