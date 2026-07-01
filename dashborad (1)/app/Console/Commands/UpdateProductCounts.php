<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Brand;
use Illuminate\Console\Command;

class UpdateProductCounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:product-counts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update product counts for categories and brands';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating product counts...');

        // Update category product counts
        $this->info('Updating categories...');
        $categories = Category::all();
        $categoryCount = 0;
        
        foreach ($categories as $category) {
            $productCount = $category->products()->count();
            $category->update(['product_count' => $productCount]);
            $categoryCount++;
            
            if ($categoryCount % 10 === 0) {
                $this->line("Updated {$categoryCount} categories...");
            }
        }
        
        $this->info("Updated {$categoryCount} categories total.");

        // Update brand product counts
        $this->info('Updating brands...');
        $brands = Brand::all();
        $brandCount = 0;
        
        foreach ($brands as $brand) {
            $productCount = $brand->products()->count();
            $brand->update(['product_count' => $productCount]);
            $brandCount++;
            
            if ($brandCount % 10 === 0) {
                $this->line("Updated {$brandCount} brands...");
            }
        }
        
        $this->info("Updated {$brandCount} brands total.");
        
        $this->info('Product count update completed successfully!');
        
        return Command::SUCCESS;
    }
}