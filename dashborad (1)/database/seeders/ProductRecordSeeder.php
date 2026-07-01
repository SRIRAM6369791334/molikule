?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductRecord;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductRecordSeeder extends Seeder
{
/**
* Run the database seeds.
*/
public function run(): void
{
// 1. Clear existing records to prevent duplicates during seeding
ProductRecord::truncate();

// 2. Fetch all existing products with their relationships
$products = Product::with(['category', 'brand', 'variants'])->get();

$this->command->info('Seeding ' . $products->count() . ' product snapshots...');

foreach ($products as $product) {
$category = $product->category;
$brand = $product->brand;
$variants = $product->variants;

ProductRecord::create([
'product_name' => $product->name,
'sku' => $product->sku,
'category_name' => $category->category_name ?? 'N/A',
'brand_name' => $brand->brand_name ?? 'N/A',
'product_full_data' => $product->toArray(),
'category_full_data' => $category ? $category->toArray() : [],
'brand_full_data' => $brand ? $brand->toArray() : [],
'variants_full_data' => $variants->toArray(),
'created_at' => $product->created_at, // Preserve original creation time
'updated_at' => $product->updated_at,
]);
}

$this->command->info('Successfully seeded ' . $products->count() . ' snapshots into product_records table.');
}
}