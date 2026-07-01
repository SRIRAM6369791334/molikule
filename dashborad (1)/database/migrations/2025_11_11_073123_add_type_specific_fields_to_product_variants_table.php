<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('size_length')->nullable()->after('variant_color_code');
            $table->string('size_width')->nullable()->after('size_length');
            $table->string('size_height')->nullable()->after('size_width');
            $table->decimal('size_weight', 10, 2)->nullable()->after('size_height');
            $table->string('size_unit', 20)->default('cm')->after('size_weight');
            $table->string('size_chart')->nullable()->after('size_unit');
            
            $table->string('material_composition')->nullable()->after('size_chart');
            $table->text('material_care_instructions')->nullable()->after('material_composition');
            $table->string('material_texture')->nullable()->after('material_care_instructions');
            $table->boolean('material_eco_friendly')->default(false)->after('material_texture');
            
            $table->string('style_pattern')->nullable()->after('material_eco_friendly');
            $table->string('style_occasion')->nullable()->after('style_pattern');
            $table->string('style_season')->nullable()->after('style_occasion');
            
            $table->string('color_name')->nullable()->after('style_season');
            $table->string('color_family')->nullable()->after('color_name');
            $table->string('color_pantone')->nullable()->after('color_family');
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn([
                'size_length',
                'size_width',
                'size_height',
                'size_weight',
                'size_unit',
                'size_chart',
                'material_composition',
                'material_care_instructions',
                'material_texture',
                'material_eco_friendly',
                'style_pattern',
                'style_occasion',
                'style_season',
                'color_name',
                'color_family',
                'color_pantone'
            ]);
        });
    }
};
