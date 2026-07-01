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
        Schema::table('brands', function (Blueprint $table) {
            if (!Schema::hasColumn('brands', 'slug')) {
                $table->string('slug', 255)->nullable()->after('brand_name');
            }
            if (!Schema::hasColumn('brands', 'short_description')) {
                $table->text('short_description')->nullable()->after('description');
            }
            if (!Schema::hasColumn('brands', 'meta_title')) {
                $table->string('meta_title', 60)->nullable()->after('description');
            }
            if (!Schema::hasColumn('brands', 'meta_description')) {
                $table->text('meta_description', 160)->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('brands', 'meta_keywords')) {
                $table->text('meta_keywords')->nullable()->after('meta_description');
            }
            
            if (!Schema::hasColumn('brands', 'brand_logo') && Schema::hasColumn('brands', 'logo_url')) {
                $table->string('brand_logo', 255)->nullable()->after('logo_url');
            }
            if (!Schema::hasColumn('brands', 'brand_banner')) {
                $table->string('brand_banner', 255)->nullable()->after('brand_logo');
            }
            if (!Schema::hasColumn('brands', 'brand_video_url')) {
                $table->string('brand_video_url', 500)->nullable()->after('brand_banner');
            }
            if (!Schema::hasColumn('brands', 'website_url')) {
                $table->string('website_url', 500)->nullable()->after('brand_video_url');
            }
            if (!Schema::hasColumn('brands', 'facebook_url')) {
                $table->string('facebook_url', 500)->nullable()->after('website_url');
            }
            if (!Schema::hasColumn('brands', 'instagram_url')) {
                $table->string('instagram_url', 500)->nullable()->after('facebook_url');
            }
            if (!Schema::hasColumn('brands', 'twitter_url')) {
                $table->string('twitter_url', 500)->nullable()->after('instagram_url');
            }
            if (!Schema::hasColumn('brands', 'youtube_url')) {
                $table->string('youtube_url', 500)->nullable()->after('twitter_url');
            }
            if (!Schema::hasColumn('brands', 'linkedin_url')) {
                $table->string('linkedin_url', 500)->nullable()->after('youtube_url');
            }
            if (!Schema::hasColumn('brands', 'contact_email')) {
                $table->string('contact_email', 255)->nullable()->after('linkedin_url');
            }
            if (!Schema::hasColumn('brands', 'contact_phone')) {
                $table->string('contact_phone', 20)->nullable()->after('contact_email');
            }
            if (!Schema::hasColumn('brands', 'support_email')) {
                $table->string('support_email', 255)->nullable()->after('contact_phone');
            }
            if (!Schema::hasColumn('brands', 'brand_type')) {
                $table->string('brand_type', 50)->default('traditional')->after('support_email');
            }
            if (!Schema::hasColumn('brands', 'is_featured')) {
                $table->tinyInteger('is_featured')->default(0)->after('brand_type');
            }
            if (!Schema::hasColumn('brands', 'show_on_homepage')) {
                $table->tinyInteger('show_on_homepage')->default(0)->after('is_featured');
            }
            if (!Schema::hasColumn('brands', 'is_verified')) {
                $table->tinyInteger('is_verified')->default(0)->after('show_on_homepage');
            }
            if (!Schema::hasColumn('brands', 'is_premium')) {
                $table->tinyInteger('is_premium')->default(0)->after('is_verified');
            }
            if (!Schema::hasColumn('brands', 'product_count')) {
                $table->unsignedInteger('product_count')->default(0)->after('is_premium');
            }
            if (!Schema::hasColumn('brands', 'view_count')) {
                $table->unsignedInteger('view_count')->default(0)->after('product_count');
            }
            if (!Schema::hasColumn('brands', 'follower_count')) {
                $table->unsignedInteger('follower_count')->default(0)->after('view_count');
            }
            if (!Schema::hasColumn('brands', 'rating_average')) {
                $table->decimal('rating_average', 3, 2)->default(0.00)->after('follower_count');
            }
            if (!Schema::hasColumn('brands', 'rating_count')) {
                $table->unsignedInteger('rating_count')->default(0)->after('rating_average');
            }
            if (!Schema::hasColumn('brands', 'country_of_origin')) {
                $table->string('country_of_origin', 100)->nullable()->after('rating_count');
            }
            if (!Schema::hasColumn('brands', 'headquarters_address')) {
                $table->string('headquarters_address', 500)->nullable()->after('country_of_origin');
            }
            if (!Schema::hasColumn('brands', 'established_year')) {
                $table->string('established_year', 4)->nullable()->after('headquarters_address');
            }
            if (!Schema::hasColumn('brands', 'brand_story')) {
                $table->longText('brand_story')->nullable()->after('established_year');
            }
            if (!Schema::hasColumn('brands', 'brand_values')) {
                $table->text('brand_values')->nullable()->after('brand_story');
            }
            if (!Schema::hasColumn('brands', 'brand_mission')) {
                $table->text('brand_mission')->nullable()->after('brand_values');
            }
            if (!Schema::hasColumn('brands', 'brand_vision')) {
                $table->text('brand_vision')->nullable()->after('brand_mission');
            }
            if (!Schema::hasColumn('brands', 'og_title')) {
                $table->text('og_title')->nullable()->after('brand_vision');
            }
            if (!Schema::hasColumn('brands', 'og_description')) {
                $table->text('og_description')->nullable()->after('og_title');
            }
            if (!Schema::hasColumn('brands', 'og_image')) {
                $table->string('og_image', 255)->nullable()->after('og_description');
            }
            if (!Schema::hasColumn('brands', 'og_type')) {
                $table->string('og_type', 50)->default('website')->after('og_image');
            }
            if (!Schema::hasColumn('brands', 'schema_markup')) {
                $table->text('schema_markup')->nullable()->after('og_type');
            }
            if (!Schema::hasColumn('brands', 'url_structure')) {
                $table->string('url_structure', 500)->nullable()->after('schema_markup');
            }
            if (!Schema::hasColumn('brands', 'canonical_url')) {
                $table->string('canonical_url', 500)->nullable()->after('url_structure');
            }
            if (!Schema::hasColumn('brands', 'index_status')) {
                $table->enum('index_status', ['index', 'noindex', 'follow', 'nofollow'])->default('index')->after('canonical_url');
            }
            if (!Schema::hasColumn('brands', 'change_frequency')) {
                $table->string('change_frequency', 20)->default('weekly')->after('index_status');
            }
            if (!Schema::hasColumn('brands', 'last_modified')) {
                $table->timestamp('last_modified')->nullable()->after('change_frequency');
            }
            if (!Schema::hasColumn('brands', 'custom_fields')) {
                $table->longText('custom_fields')->nullable()->after('last_modified');
            }
            if (!Schema::hasColumn('brands', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('custom_fields');
            }
        });
        
        $this->addIndexIfNotExists('brands', ['slug'], 'brands_slug_unique', true);
        $this->addIndexIfNotExists('brands', ['is_featured'], 'brands_is_featured_index');
        $this->addIndexIfNotExists('brands', ['show_on_homepage'], 'brands_show_on_homepage_index');
        $this->addIndexIfNotExists('brands', ['is_verified'], 'brands_is_verified_index');
        $this->addIndexIfNotExists('brands', ['is_premium'], 'brands_is_premium_index');
        $this->addIndexIfNotExists('brands', ['product_count'], 'brands_product_count_index');
        $this->addIndexIfNotExists('brands', ['view_count'], 'brands_view_count_index');
        $this->addIndexIfNotExists('brands', ['follower_count'], 'brands_follower_count_index');
        $this->addIndexIfNotExists('brands', ['rating_average'], 'brands_rating_average_index');
        $this->addIndexIfNotExists('brands', ['rating_count'], 'brands_rating_count_index');
        $this->addIndexIfNotExists('brands', ['brand_type'], 'brands_brand_type_index');
        $this->addIndexIfNotExists('brands', ['country_of_origin'], 'brands_country_origin_index');
        $this->addIndexIfNotExists('brands', ['established_year'], 'brands_established_year_index');
        $this->addIndexIfNotExists('brands', ['is_active'], 'brands_is_active_index');
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
        Schema::table('brands', function (Blueprint $table) {
            // Drop indexes
            $table->dropUnique('brands_slug_unique');
            $table->dropIndex('brands_is_featured_index');
            $table->dropIndex('brands_show_on_homepage_index');
            $table->dropIndex('brands_is_verified_index');
            $table->dropIndex('brands_is_premium_index');
            $table->dropIndex('brands_product_count_index');
            $table->dropIndex('brands_view_count_index');
            $table->dropIndex('brands_follower_count_index');
            $table->dropIndex('brands_rating_average_index');
            $table->dropIndex('brands_rating_count_index');
            $table->dropIndex('brands_brand_type_index');
            $table->dropIndex('brands_country_origin_index');
            $table->dropIndex('brands_established_year_index');
            $table->dropIndex('brands_active_index');
            
            // Drop columns
            $table->dropColumn([
                'slug',
                'short_description',
                'meta_title',
                'meta_description',
                'meta_keywords',
                'brand_logo',
                'brand_banner',
                'brand_video_url',
                'website_url',
                'facebook_url',
                'instagram_url',
                'twitter_url',
                'youtube_url',
                'linkedin_url',
                'contact_email',
                'contact_phone',
                'support_email',
                'brand_type',
                'is_featured',
                'show_on_homepage',
                'is_verified',
                'is_premium',
                'product_count',
                'view_count',
                'follower_count',
                'rating_average',
                'rating_count',
                'country_of_origin',
                'headquarters_address',
                'established_year',
                'brand_story',
                'brand_values',
                'brand_mission',
                'brand_vision',
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
                'custom_fields',
                'created_by'
            ]);
        });
    }
};