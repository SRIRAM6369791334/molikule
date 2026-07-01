<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('banners', function (Blueprint $table) {
            if (!Schema::hasColumn('banners', 'slug')) {
                $table->string('slug', 255)->unique()->nullable()->after('title');
            }
            if (!Schema::hasColumn('banners', 'banner_type')) {
                $table->string('banner_type')->default('promotional')->after('image_url');
            }
            if (!Schema::hasColumn('banners', 'subtitle')) {
                $table->string('subtitle')->nullable()->after('banner_type');
            }
            if (!Schema::hasColumn('banners', 'mini_image_url') && !Schema::hasColumn('banners', 'minimage_url')) {
                $table->string('mini_image_url')->nullable()->after('subtitle');
            }
            if (!Schema::hasColumn('banners', 'target_type')) {
                $table->string('target_type')->nullable()->after('subtitle');
            }
            if (!Schema::hasColumn('banners', 'target_id')) {
                $table->unsignedBigInteger('target_id')->nullable()->after('target_type');
            }
            if (!Schema::hasColumn('banners', 'target_url')) {
                $table->string('target_url')->nullable()->after('target_id');
            }
            if (!Schema::hasColumn('banners', 'button_text')) {
                $table->string('button_text')->nullable()->after('target_url');
            }
            if (!Schema::hasColumn('banners', 'background_color')) {
                $table->string('background_color')->nullable()->after('button_text');
            }
            if (!Schema::hasColumn('banners', 'text_color')) {
                $table->string('text_color')->nullable()->after('background_color');
            }
            if (!Schema::hasColumn('banners', 'starts_at')) {
                $table->timestamp('starts_at')->nullable()->after('text_color');
            }
            if (!Schema::hasColumn('banners', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('starts_at');
            }
            if (!Schema::hasColumn('banners', 'show_on_hover')) {
                $table->boolean('show_on_hover')->default(false)->after('expires_at');
            }
            if (!Schema::hasColumn('banners', 'display_duration')) {
                $table->integer('display_duration')->nullable()->after('show_on_hover');
            }
            if (!Schema::hasColumn('banners', 'impression_count')) {
                $table->integer('impression_count')->default(0)->after('display_duration');
            }
            if (!Schema::hasColumn('banners', 'click_count')) {
                $table->integer('click_count')->default(0)->after('impression_count');
            }
            if (!Schema::hasColumn('banners', 'ctr')) {
                $table->decimal('ctr', 5, 2)->default(0)->after('click_count');
            }
            if (!Schema::hasColumn('banners', 'alt_text')) {
                $table->string('alt_text')->nullable()->after('ctr');
            }
            if (!Schema::hasColumn('banners', 'css_class')) {
                $table->string('css_class')->nullable()->after('alt_text');
            }
            if (!Schema::hasColumn('banners', 'custom_data')) {
                $table->json('custom_data')->nullable()->after('css_class');
            }
        });
        
        $this->addIndexIfNotExists('banners', ['is_active', 'starts_at', 'expires_at'], 'banners_active_schedule_index');
        $this->addIndexIfNotExists('banners', ['banner_type'], 'banners_type_index');
        $this->addIndexIfNotExists('banners', ['slug'], 'banners_slug_index');
        $this->addIndexIfNotExists('banners', ['impression_count'], 'banners_impressions_index');
        $this->addIndexIfNotExists('banners', ['click_count'], 'banners_clicks_index');
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
        Schema::table('banners', function (Blueprint $table) {
            try {
                $table->dropIndex(['is_active', 'starts_at', 'expires_at']);
                $table->dropIndex(['banner_type']);
                $table->dropIndex(['slug']);
                $table->dropIndex(['impression_count']);
                $table->dropIndex(['click_count']);
            } catch (\Exception $e) {
                // Indexes might not exist, ignore error
            }
            
            $table->dropColumn([
                'slug', 'banner_type', 'subtitle', 'mini_image_url', 'target_type',
                'target_id', 'target_url', 'button_text', 'background_color', 'text_color',
                'starts_at', 'expires_at', 'show_on_hover', 'display_duration',
                'impression_count', 'click_count', 'ctr', 'alt_text', 'css_class', 'custom_data'
            ]);
        });
    }
};