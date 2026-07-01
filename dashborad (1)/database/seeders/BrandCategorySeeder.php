<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BrandCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path relative to the 'uploads' disk
        $placeholderPath = 'placeholder-category.png';

        // 1. Seed Brands
        $brands = [
            'Molikule',
            'Pure Air Co',
            'AeroGuard',
            'FreshBreeze',
            'SkyFlow',
            'EcoBreath',
            'Nanotech Shield',
            'VitaPure',
            'UrbanFilter',
            'HomeWellness'
        ];

        foreach ($brands as $index => $name) {
            Brand::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'brand_name'       => $name,
                    'logo'             => $placeholderPath,
                    'is_active'        => true,
                    'is_featured'      => $index < 5,
                    'show_on_homepage' => true,
                    'sort_order'       => $index,
                ]
            );
        }

        // 2. Seed Categories
        $categories = [
            'Air Purifiers'   => ['Portable', 'Whole House', 'Car Purifiers'],
            'Replacement Filters' => ['HEPA Filters', 'Carbon Filters', 'Pre-filters'],
            'Smart Accessories'   => ['Sensors', 'Controllers', 'Mounts'],
            'Industrial Solutions' => ['Factory Purifiers', 'Office Grade', 'Hospital Grade'],
            'Wellness Kits'   => ['Starter Packs', 'Family Bundles'],
        ];

        $catOrder = 0;
        foreach ($categories as $parentName => $subCategories) {
            $parent = Category::updateOrCreate(
                ['slug' => Str::slug($parentName)],
                [
                    'category_name'    => $parentName,
                    'image'            => $placeholderPath,
                    'is_active'        => true,
                    'parent_id'        => null,
                    'is_featured'      => true,
                    'show_on_homepage' => true,
                    'sort_order'       => $catOrder++,
                ]
            );

            foreach ($subCategories as $subIndex => $subName) {
                Category::updateOrCreate(
                    ['slug' => Str::slug($parentName . ' ' . $subName)],
                    [
                        'category_name'    => $subName,
                        'image'            => $placeholderPath,
                        'is_active'        => true,
                        'parent_id'        => $parent->category_id,
                        'is_featured'      => false,
                        'show_on_homepage' => false,
                        'sort_order'       => $subIndex,
                    ]
                );
            }
        }

        $this->command->info('Brands and Categories seeded successfully using placeholder image.');
    }
}
