<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn([
                'size_length',
                'size_width',
                'size_height',
                'size_weight',
                'size_chart',
                'material_composition',
                'material_care_instructions',
                'material_texture',
                'material_eco_friendly',
                'style_pattern',
                'style_occasion',
                'style_season',
                'color_family',
                'color_pantone'
            ]);
            
            $table->string('size_value')->nullable()->after('variant_color_code');
            $table->string('material_name')->nullable()->after('size_unit');
            $table->string('style_name')->nullable()->after('material_name');
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['size_value', 'material_name', 'style_name']);
            
            $table->string('size_length')->nullable();
            $table->string('size_width')->nullable();
            $table->string('size_height')->nullable();
            $table->decimal('size_weight', 10, 2)->nullable();
            $table->string('size_chart')->nullable();
            $table->string('material_composition')->nullable();
            $table->text('material_care_instructions')->nullable();
            $table->string('material_texture')->nullable();
            $table->boolean('material_eco_friendly')->default(false);
            $table->string('style_pattern')->nullable();
            $table->string('style_occasion')->nullable();
            $table->string('style_season')->nullable();
            $table->string('color_family')->nullable();
            $table->string('color_pantone')->nullable();
        });
    }
};
