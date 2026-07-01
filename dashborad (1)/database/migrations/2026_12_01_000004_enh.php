<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('banners', function (Blueprint $table) {
            // Enhanced banner types
            $table->enum('banner_type', ['hero', 'promotional', 'category', 'brand', 'product', 'announcement'])
                  ->default('promotional')
                  ->after('title');
            
            // Enhanced targeting
            $table->string('target_type')->nullable()->after('link_url'); // 'product', 'category', 'brand', 'url'
            $table->unsignedBigInteger('target_id')->nullable()->after('target_type');
            $table->string('target_url')->nullable()->after('target_id');
            
            // Enhanced content
            $table->string('subtitle')->nullable()->after('description');
            $table->text('button_text')->nullable()->after('link_text');
            $table->string('background_color')->nullable()->after('button_text');
            $table->string('text_color')->nullable()->after('background_color');
            
            // Enhanced scheduling
            $table->timestamp('starts_at')->nullable()->change();
            $table->timestamp('expires_at')->nullable()->change();
            $table->boolean('show_on_hover')->default(0)->after('is_active');
            $table->integer('display_duration')->default(5)->after('show_on_hover'); // in seconds
            
            // Analytics and performance
            $table->integer('impression_count')->default(0)->after('display_duration');
            $table->integer('click_count')->default(0)->after('impression_count');
            $table->decimal('ctr', 5, 2)->default(0)->after('click_count'); // click-through rate
            
            // SEO and metadata
            $table->text('alt_text')->nullable()->after('text_color');
            $table->string('css_class')->nullable()->after('alt_text');
            $table->json('custom_data')->nullable()->after('css_class');
            
            // Add indexes
            $table->index(['banner_type', 'is_active']);
            $table->index(['target_type', 'target_id']);
            $table->index(['is_active', 'starts_at', 'expires_at']);
            $table->index('position');
            $table->index('sort_order');
        });
    }

    public function down()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropIndex(['banner_type', 'is_active']);
            $table->dropIndex(['target_type', 'target_id']);
            $table->dropIndex(['is_active', 'starts_at', 'expires_at']);
            $table->dropIndex('position');
            $table->dropIndex('sort_order');
            
            $table->dropColumn([
                'banner_type', 'target_type', 'target_id', 'target_url',
                'subtitle', 'button_text', 'background_color', 'text_color',
                'show_on_hover', 'display_duration', 'impression_count',
                'click_count', 'ctr', 'alt_text', 'css_class', 'custom_data'
            ]);
        });
    }
};