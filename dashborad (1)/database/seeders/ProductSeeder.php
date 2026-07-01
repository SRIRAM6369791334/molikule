<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    // Base path relative to public/
    private const IMG_BC   = 'assets/images/resource/competitors/britishclean/';
    private const IMG_VISTA = 'assets/images/resource/competitors/vista/';

    public function run(): void
    {
        // ── Clean existing data ─────────────────────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('product_variants')->truncate();
        DB::table('products')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // ── Categories ─────────────────────────────────────────────
        $catHouseKeeping = $this->upsertCategory('Housekeeping',  'housekeeping');
        $catLaundry      = $this->upsertCategory('Laundry Care',  'laundry-care');
        $catHandwash     = $this->upsertCategory('Hand Wash',     'hand-wash');
        $catAutoCare     = $this->upsertCategory('Auto Care',     'auto-care');
        $catKitchen      = $this->upsertCategory('Kitchen Care',  'kitchen-care');

        // ── Brands ─────────────────────────────────────────────────
        $brandBC    = $this->upsertBrand('British Clean', 'british-clean');
        $brandVista = $this->upsertBrand('Vista',         'vista');

        // ── Products ────────────────────────────────────────────────
        $products = [
            [
                'name'              => 'LD14 Ultra Laundry Liquid',
                'slug'              => 'ld14-ultra-laundry-liquid',
                'short_description' => 'Advanced formula laundry liquid for brilliant whiteness and lasting freshness.',
                'description'       => '<p>LD14 Ultra Laundry Liquid is engineered for superior cleaning performance. Its advanced enzymes break down tough stains while protecting fabric fibres.</p><h5>Features:</h5><ul><li>Removes tough stains in one wash</li><li>Gentle on all fabric types</li><li>Long-lasting fresh fragrance</li><li>Suitable for front-load and top-load machines</li></ul>',
                'image'             => self::IMG_BC . 'ld14_laundry.png',
                'category_id'       => $catLaundry,
                'brand_id'          => $brandBC,
                'mrp_price'         => 249.00,
                'compare_price'     => 299.00,
                'stock_quantity'    => 150,
                'is_featured'       => true,
                'badge'             => 'Sale',
                'variants'          => [
                    ['value' => '500', 'variant_unit' => 'ml',    'mrp_price' => 149.00, 'compare_price' => 175.00, 'qty' => 60],
                    ['value' => '1',   'variant_unit' => 'liter', 'mrp_price' => 249.00, 'compare_price' => 299.00, 'qty' => 50],
                    ['value' => '5',   'variant_unit' => 'liter', 'mrp_price' => 999.00, 'compare_price' => 1199.00,'qty' => 40],
                ],
            ],
            [
                'name'              => 'DW9 Dishwash Liquid',
                'slug'              => 'dw9-dishwash-liquid',
                'short_description' => 'Cuts through grease instantly for sparkling clean dishes.',
                'description'       => '<p>DW9 Dishwash Liquid delivers powerful degreasing action, leaving your dishes spotlessly clean. Skin-friendly lemon formula safe for daily use.</p><h5>Features:</h5><ul><li>Removes grease instantly</li><li>Skin-friendly pH-balanced</li><li>Long-lasting foam</li><li>Fresh lemon fragrance</li></ul>',
                'image'             => self::IMG_BC . 'dw9_dishwash.png',
                'category_id'       => $catKitchen,
                'brand_id'          => $brandBC,
                'mrp_price'         => 149.00,
                'compare_price'     => null,
                'stock_quantity'    => 200,
                'is_featured'       => true,
                'badge'             => 'New',
                'variants'          => [
                    ['value' => '250', 'variant_unit' => 'ml',    'mrp_price' => 89.00,  'compare_price' => null,  'qty' => 80],
                    ['value' => '500', 'variant_unit' => 'ml',    'mrp_price' => 149.00, 'compare_price' => null,  'qty' => 70],
                    ['value' => '1',   'variant_unit' => 'liter', 'mrp_price' => 259.00, 'compare_price' => null,  'qty' => 50],
                ],
            ],
            [
                'name'              => 'HW10 Silk Handwash',
                'slug'              => 'hw10-silk-handwash',
                'short_description' => 'Luxurious silk-protein handwash that leaves hands soft and nourished.',
                'description'       => '<p>HW10 Silk Handwash combines powerful germ protection with the nourishing benefits of silk proteins.</p><h5>Features:</h5><ul><li>Kills 99.9% germs</li><li>Enriched with silk proteins</li><li>Dermatologically tested</li><li>Pleasant floral fragrance</li></ul>',
                'image'             => self::IMG_BC . 'hw10_handwash.png',
                'category_id'       => $catHandwash,
                'brand_id'          => $brandBC,
                'mrp_price'         => 129.00,
                'compare_price'     => null,
                'stock_quantity'    => 250,
                'is_featured'       => true,
                'badge'             => 'Popular',
                'variants'          => [
                    ['value' => '250', 'variant_unit' => 'ml',    'mrp_price' => 79.00,  'compare_price' => null,  'qty' => 100],
                    ['value' => '500', 'variant_unit' => 'ml',    'mrp_price' => 129.00, 'compare_price' => null,  'qty' => 90],
                    ['value' => '1',   'variant_unit' => 'liter', 'mrp_price' => 219.00, 'compare_price' => null,  'qty' => 60],
                ],
            ],
            [
                'name'              => 'Vista Ceramic Car Spray',
                'slug'              => 'vista-ceramic-car-spray',
                'short_description' => 'Professional-grade ceramic car coating spray for lasting shine.',
                'description'       => '<p>Vista Ceramic Spray provides a durable ceramic-based protective layer on your vehicle\'s paint. Hydrophobic formula repels water and dust.</p><h5>Features:</h5><ul><li>Hydrophobic nano-ceramic</li><li>UV protection coating</li><li>Lasts up to 6 months</li><li>Easy spray-and-wipe application</li></ul>',
                'image'             => self::IMG_VISTA . 'ceramic_spray.jpg',
                'category_id'       => $catAutoCare,
                'brand_id'          => $brandVista,
                'mrp_price'         => 399.00,
                'compare_price'     => 499.00,
                'stock_quantity'    => 80,
                'is_featured'       => false,
                'badge'             => 'Sale',
                'variants'          => [
                    ['value' => '200', 'variant_unit' => 'ml',    'mrp_price' => 249.00, 'compare_price' => 299.00, 'qty' => 40],
                    ['value' => '500', 'variant_unit' => 'ml',    'mrp_price' => 399.00, 'compare_price' => 499.00, 'qty' => 40],
                ],
            ],
            [
                'name'              => 'S2 Multi-Surface Cleaner',
                'slug'              => 's2-multi-surface-cleaner',
                'short_description' => 'Powerful all-purpose cleaner for every surface in your home.',
                'description'       => '<p>S2 Multi-Surface Cleaner tackles dirt, grime and bacteria on tiles, countertops, glass and more. One versatile product for a spotless home.</p><h5>Features:</h5><ul><li>Works on 10+ surface types</li><li>Anti-bacterial formula</li><li>No rinsing required</li><li>Fresh citrus fragrance</li></ul>',
                'image'             => self::IMG_BC . 's2_all_purpose.png',
                'category_id'       => $catHouseKeeping,
                'brand_id'          => $brandBC,
                'mrp_price'         => 199.00,
                'compare_price'     => null,
                'stock_quantity'    => 180,
                'is_featured'       => false,
                'badge'             => 'New',
                'variants'          => [
                    ['value' => '500', 'variant_unit' => 'ml',    'mrp_price' => 129.00, 'compare_price' => null,  'qty' => 70],
                    ['value' => '1',   'variant_unit' => 'liter', 'mrp_price' => 199.00, 'compare_price' => null,  'qty' => 60],
                    ['value' => '5',   'variant_unit' => 'liter', 'mrp_price' => 799.00, 'compare_price' => null,  'qty' => 50],
                ],
            ],
            [
                'name'              => 'S1 Deep Floor Care',
                'slug'              => 's1-deep-floor-care',
                'short_description' => 'Deep-action floor cleaner that removes stubborn stains and leaves brilliant shine.',
                'description'       => '<p>S1 Deep Floor Care penetrates deep into floor surfaces to remove grime, oil and tough stains. Triple-action formula leaves floors gleaming and germ-free.</p><h5>Features:</h5><ul><li>Triple-action deep clean</li><li>Anti-bacterial protection</li><li>Leaves brilliant shine</li><li>Pine fresh fragrance</li></ul>',
                'image'             => self::IMG_BC . 's1_floor_cleaner.png',
                'category_id'       => $catHouseKeeping,
                'brand_id'          => $brandBC,
                'mrp_price'         => 249.00,
                'compare_price'     => 299.00,
                'stock_quantity'    => 120,
                'is_featured'       => false,
                'badge'             => 'Popular',
                'variants'          => [
                    ['value' => '1',   'variant_unit' => 'liter', 'mrp_price' => 249.00, 'compare_price' => 299.00,  'qty' => 70],
                    ['value' => '5',   'variant_unit' => 'liter', 'mrp_price' => 999.00, 'compare_price' => 1199.00, 'qty' => 50],
                ],
            ],
            [
                'name'              => 'Green Apple Handwash',
                'slug'              => 'green-apple-handwash',
                'short_description' => 'Refreshing green apple scented handwash with powerful germ protection.',
                'description'       => '<p>Green Apple Handwash combines a burst of fresh apple fragrance with powerful antibacterial protection. Safe and moisturising for daily family use.</p><h5>Features:</h5><ul><li>Kills 99.9% germs</li><li>Refreshing green apple scent</li><li>Moisturising aloe vera extract</li><li>Suitable for all skin types</li></ul>',
                'image'             => self::IMG_BC . 'green_apple.png',
                'category_id'       => $catHandwash,
                'brand_id'          => $brandBC,
                'mrp_price'         => 99.00,
                'compare_price'     => null,
                'stock_quantity'    => 300,
                'is_featured'       => true,
                'badge'             => 'Popular',
                'variants'          => [
                    ['value' => '250', 'variant_unit' => 'ml',    'mrp_price' => 59.00,  'compare_price' => null,  'qty' => 120],
                    ['value' => '500', 'variant_unit' => 'ml',    'mrp_price' => 99.00,  'compare_price' => null,  'qty' => 100],
                    ['value' => '1',   'variant_unit' => 'liter', 'mrp_price' => 179.00, 'compare_price' => null,  'qty' => 80],
                ],
            ],
            [
                'name'              => 'Strawberry Chill Handwash',
                'slug'              => 'strawberry-chill-handwash',
                'short_description' => 'Sweet strawberry scented handwash that kids love and parents trust.',
                'description'       => '<p>Strawberry Chill Handwash has a sweet strawberry fragrance. Gentle on kids\' skin while providing complete germ protection.</p><h5>Features:</h5><ul><li>Gentle on children\'s skin</li><li>Sweet strawberry fragrance</li><li>Kills 99.9% germs</li><li>pH-balanced formula</li></ul>',
                'image'             => self::IMG_BC . 'strawberry_chill.png',
                'category_id'       => $catHandwash,
                'brand_id'          => $brandBC,
                'mrp_price'         => 99.00,
                'compare_price'     => null,
                'stock_quantity'    => 280,
                'is_featured'       => true,
                'badge'             => 'New',
                'variants'          => [
                    ['value' => '250', 'variant_unit' => 'ml',    'mrp_price' => 59.00,  'compare_price' => null,  'qty' => 110],
                    ['value' => '500', 'variant_unit' => 'ml',    'mrp_price' => 99.00,  'compare_price' => null,  'qty' => 100],
                    ['value' => '1',   'variant_unit' => 'liter', 'mrp_price' => 179.00, 'compare_price' => null,  'qty' => 70],
                ],
            ],
        ];

        // ── Insert products + variants ───────────────────────────────
        foreach ($products as $productData) {
            $variants = $productData['variants'];
            unset($productData['variants']);

            $productData['created_at'] = now();
            $productData['updated_at'] = now();

            $productId = DB::table('products')->insertGetId($productData);

            foreach ($variants as $v) {
                DB::table('product_variants')->insert([
                    'product_id'     => $productId,
                    'value'          => $v['value'],
                    'variant_value'  => $v['value'],
                    'variant_unit'   => $v['variant_unit'],
                    'variant_name'   => $v['value'] . ' ' . $v['variant_unit'],
                    'variant_type'   => 'Volume',
                    'mrp_price'      => $v['mrp_price'],
                    'compare_price'  => $v['compare_price'] ?? null,
                    'stock_quantity' => $v['qty'],
                    'active'         => true,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }
        }

        $variantCount = DB::table('product_variants')->count();
        $this->command->info("✅ Seeded 8 products with {$variantCount} variants.");
    }

    // ── Helpers ──────────────────────────────────────────────────────
    private function upsertCategory(string $name, string $slug): int
    {
        $existing = DB::table('categories')->where('slug', $slug)->first();
        if ($existing) return $existing->category_id;

        return DB::table('categories')->insertGetId([
            'category_name'    => $name,
            'slug'             => $slug,
            'is_active'        => true,
            'is_featured'      => true,
            'show_on_homepage' => true,
            'parent_id'        => null,
            'sort_order'       => 0,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }

    private function upsertBrand(string $name, string $slug): int
    {
        $existing = DB::table('brands')->where('slug', $slug)->first();
        if ($existing) return $existing->brand_id;

        return DB::table('brands')->insertGetId([
            'brand_name'       => $name,
            'slug'             => $slug,
            'is_active'        => true,
            'is_featured'      => true,
            'show_on_homepage' => true,
            'sort_order'       => 0,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }
}
