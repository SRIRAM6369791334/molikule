<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VariantAttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Size Attribute
        $sizeId = DB::table('variant_attributes')->insertGetId([
            'name' => 'Size',
            'slug' => 'size',
            'input_type' => 'radio',
            'description' => 'Product size options',
            'is_required' => true,
            'is_visible' => true,
            'use_in_product_listing' => true,
            'sort_order' => 1,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Size values
        $sizes = [
            ['value' => 'Extra Small', 'short_code' => 'XS', 'sort' => 1],
            ['value' => 'Small', 'short_code' => 'S', 'sort' => 2],
            ['value' => 'Medium', 'short_code' => 'M', 'sort' => 3],
            ['value' => 'Large', 'short_code' => 'L', 'sort' => 4],
            ['value' => 'Extra Large', 'short_code' => 'XL', 'sort' => 5],
            ['value' => '2X Large', 'short_code' => '2XL', 'sort' => 6, 'price' => 50],
            ['value' => '3X Large', 'short_code' => '3XL', 'sort' => 7, 'price' => 100],
        ];

        foreach ($sizes as $size) {
            DB::table('variant_attribute_values')->insert([
                'attribute_id' => $sizeId,
                'value' => $size['value'],
                'slug' => Str::slug($size['value']),
                'display_name' => $size['value'] . ' (' . $size['short_code'] . ')',
                'short_code' => $size['short_code'],
                'price_modifier' => $size['price'] ?? 0,
                'price_modifier_type' => 'fixed',
                'sort_order' => $size['sort'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Create Color Attribute
        $colorId = DB::table('variant_attributes')->insertGetId([
            'name' => 'Color',
            'slug' => 'color',
            'input_type' => 'swatch',
            'description' => 'Product color options',
            'is_required' => true,
            'is_visible' => true,
            'use_in_product_listing' => true,
            'sort_order' => 2,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Color values
        $colors = [
            ['value' => 'Black', 'code' => '#000000', 'short' => 'BLK', 'sort' => 1],
            ['value' => 'White', 'code' => '#FFFFFF', 'short' => 'WHT', 'sort' => 2],
            ['value' => 'Red', 'code' => '#FF0000', 'short' => 'RED', 'sort' => 3],
            ['value' => 'Blue', 'code' => '#0000FF', 'short' => 'BLU', 'sort' => 4],
            ['value' => 'Green', 'code' => '#00FF00', 'short' => 'GRN', 'sort' => 5],
            ['value' => 'Yellow', 'code' => '#FFFF00', 'short' => 'YEL', 'sort' => 6],
            ['value' => 'Orange', 'code' => '#FFA500', 'short' => 'ORG', 'sort' => 7],
            ['value' => 'Purple', 'code' => '#800080', 'short' => 'PUR', 'sort' => 8],
            ['value' => 'Pink', 'code' => '#FFC0CB', 'short' => 'PNK', 'sort' => 9],
            ['value' => 'Brown', 'code' => '#A52A2A', 'short' => 'BRN', 'sort' => 10],
            ['value' => 'Gray', 'code' => '#808080', 'short' => 'GRY', 'sort' => 11],
            ['value' => 'Navy', 'code' => '#000080', 'short' => 'NVY', 'sort' => 12],
        ];

        foreach ($colors as $color) {
            DB::table('variant_attribute_values')->insert([
                'attribute_id' => $colorId,
                'value' => $color['value'],
                'slug' => Str::slug($color['value']),
                'display_name' => $color['value'],
                'short_code' => $color['short'],
                'color_code' => $color['code'],
                'price_modifier' => 0,
                'price_modifier_type' => 'fixed',
                'sort_order' => $color['sort'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Create Material Attribute
        $materialId = DB::table('variant_attributes')->insertGetId([
            'name' => 'Material',
            'slug' => 'material',
            'input_type' => 'dropdown',
            'description' => 'Product material options',
            'is_required' => false,
            'is_visible' => true,
            'use_in_product_listing' => true,
            'sort_order' => 3,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Material values
        $materials = [
            ['value' => 'Cotton', 'short' => 'COT', 'sort' => 1],
            ['value' => 'Polyester', 'short' => 'POL', 'sort' => 2],
            ['value' => 'Wool', 'short' => 'WOL', 'sort' => 3],
            ['value' => 'Silk', 'short' => 'SLK', 'sort' => 4, 'price' => 200],
            ['value' => 'Leather', 'short' => 'LTH', 'sort' => 5, 'price' => 500],
            ['value' => 'Denim', 'short' => 'DEN', 'sort' => 6],
            ['value' => 'Linen', 'short' => 'LIN', 'sort' => 7],
            ['value' => 'Nylon', 'short' => 'NYL', 'sort' => 8],
            ['value' => 'Spandex', 'short' => 'SPX', 'sort' => 9],
            ['value' => 'Rayon', 'short' => 'RAY', 'sort' => 10],
        ];

        foreach ($materials as $material) {
            DB::table('variant_attribute_values')->insert([
                'attribute_id' => $materialId,
                'value' => $material['value'],
                'slug' => Str::slug($material['value']),
                'display_name' => $material['value'],
                'short_code' => $material['short'],
                'price_modifier' => $material['price'] ?? 0,
                'price_modifier_type' => 'fixed',
                'sort_order' => $material['sort'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. Create Style Attribute
        $styleId = DB::table('variant_attributes')->insertGetId([
            'name' => 'Style',
            'slug' => 'style',
            'input_type' => 'dropdown',
            'description' => 'Product style options',
            'is_required' => false,
            'is_visible' => true,
            'use_in_product_listing' => false,
            'sort_order' => 4,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Style values
        $styles = [
            ['value' => 'Classic', 'short' => 'CLS', 'sort' => 1],
            ['value' => 'Modern', 'short' => 'MOD', 'sort' => 2],
            ['value' => 'Vintage', 'short' => 'VIN', 'sort' => 3],
            ['value' => 'Casual', 'short' => 'CAS', 'sort' => 4],
            ['value' => 'Formal', 'short' => 'FRM', 'sort' => 5],
            ['value' => 'Sport', 'short' => 'SPT', 'sort' => 6],
            ['value' => 'Elegant', 'short' => 'ELG', 'sort' => 7],
            ['value' => 'Minimalist', 'short' => 'MIN', 'sort' => 8],
        ];

        foreach ($styles as $style) {
            DB::table('variant_attribute_values')->insert([
                'attribute_id' => $styleId,
                'value' => $style['value'],
                'slug' => Str::slug($style['value']),
                'display_name' => $style['value'],
                'short_code' => $style['short'],
                'price_modifier' => 0,
                'price_modifier_type' => 'fixed',
                'sort_order' => $style['sort'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 5. Create Volume Attribute
        $volumeId = DB::table('variant_attributes')->insertGetId([
            'name' => 'Volume',
            'slug' => 'volume',
            'input_type' => 'dropdown',
            'description' => 'Product volume/capacity options',
            'is_required' => true,
            'is_visible' => true,
            'use_in_product_listing' => true,
            'sort_order' => 5,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Volume values
        $volumes = [
            ['value' => '250 ml', 'short' => '250ML', 'sort' => 1],
            ['value' => '500 ml', 'short' => '500ML', 'sort' => 2],
            ['value' => '1 Litre', 'short' => '1L', 'sort' => 3],
            ['value' => '5 Litres', 'short' => '5L', 'sort' => 4],
            ['value' => '10 Litres', 'short' => '10L', 'sort' => 5],
            ['value' => '1 kg', 'short' => '1KG', 'sort' => 6],
            ['value' => '5 kg', 'short' => '5KG', 'sort' => 7],
        ];

        foreach ($volumes as $volume) {
            DB::table('variant_attribute_values')->insert([
                'attribute_id' => $volumeId,
                'value' => $volume['value'],
                'slug' => Str::slug($volume['value']),
                'display_name' => $volume['value'],
                'short_code' => $volume['short'],
                'price_modifier' => 0,
                'price_modifier_type' => 'fixed',
                'sort_order' => $volume['sort'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 6. Create Flavour Attribute
        $flavourId = DB::table('variant_attributes')->insertGetId([
            'name' => 'Flavour',
            'slug' => 'flavour',
            'input_type' => 'dropdown',
            'description' => 'Product flavour/fragrance options',
            'is_required' => false,
            'is_visible' => true,
            'use_in_product_listing' => true,
            'sort_order' => 6,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Flavour values
        $flavours = [
            ['value' => 'Lemon', 'short' => 'LMN', 'sort' => 1],
            ['value' => 'Rose', 'short' => 'ROS', 'sort' => 2],
            ['value' => 'Jasmine', 'short' => 'JAS', 'sort' => 3],
            ['value' => 'Lavender', 'short' => 'LAV', 'sort' => 4],
            ['value' => 'Orange', 'short' => 'ORG', 'sort' => 5],
            ['value' => 'Mogra', 'short' => 'MOG', 'sort' => 6],
            ['value' => 'Sandal', 'short' => 'SND', 'sort' => 7],
        ];

        foreach ($flavours as $flavour) {
            DB::table('variant_attribute_values')->insert([
                'attribute_id' => $flavourId,
                'value' => $flavour['value'],
                'slug' => Str::slug($flavour['value']),
                'display_name' => $flavour['value'],
                'short_code' => $flavour['short'],
                'price_modifier' => 0,
                'price_modifier_type' => 'fixed',
                'sort_order' => $flavour['sort'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ Variant attributes and values seeded successfully!');
        $this->command->info('   - Size: 7 values');
        $this->command->info('   - Color: 12 values');
        $this->command->info('   - Material: 10 values');
        $this->command->info('   - Style: 8 values');
        $this->command->info('   - Volume: 7 values');
        $this->command->info('   - Flavour: 7 values');
    }
}

