<?php

namespace App\Console\Commands;

use App\Models\OrderItem;
use Illuminate\Console\Command;

class FixOrderItemNames extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'order:fix-item-names {--dry-run : Show what would be changed without making changes}';

    /**
     * The console command description.
     */
    protected $description = 'Populate missing item_name field in order_items table from product relationships';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $query = OrderItem::whereNull('item_name');

        if ($dryRun) {
            $count = $query->count();
            $this->info("DRY RUN: Found {$count} order items with missing item_name");

            $items = $query->with('itemable')->limit(10)->get();
            foreach ($items as $item) {
                $newName = 'N/A';
                if ($item->itemable) {
                    if ($item->itemable->name) {
                        $newName = $item->itemable->name;
                    } elseif ($item->itemable->product_name) {
                        $newName = $item->itemable->product_name;
                    }
                } elseif ($item->product_name) {
                    $newName = $item->product_name;
                }
                $this->line("ID {$item->order_item_id}: Would set item_name to '{$newName}' (current: null)");
            }

            if ($count > 10) {
                $remaining = $count - 10;
                $this->line("... and {$remaining} more items");
            }

            return;
        }

        $this->info('Updating order items with missing item_name...');

        $updated = 0;
        $orderItems = $query->get();

        foreach ($orderItems as $item) {
            $newName = null;

            // Try to get from itemable relationship
            if ($item->itemable) {
                $newName = $item->itemable->name ?? $item->itemable->product_name ?? null;
            }

            // Fallback to legacy product_name column
            if (!$newName && $item->product_name) {
                $newName = $item->product_name;
            }

            if ($newName) {
                $item->update(['item_name' => $newName]);
                $updated++;
            } else {
                $this->warn("Could not determine product name for order item ID {$item->order_item_id}");
            }
        }

        $this->info("Updated {$updated} order items with item_name");
    }
}
