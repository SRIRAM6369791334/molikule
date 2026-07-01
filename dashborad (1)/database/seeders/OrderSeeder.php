<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user if none exists
        $user = User::firstOrCreate(
            ['email' => 'sriramsri1234321@gmail.com'],
            [
                'name' => 'Test Customer',
                'password' => bcrypt('password'),
            ]
        );

        // Get some products for the order
        $products = Product::take(2)->get();

        if ($products->isEmpty()) {
            $this->command->warn('No products found. Please create some products first.');
            return;
        }

        // Create a test order
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'TEST-' . time(),
            'customer_name' => 'Test Customer',
            'customer_email' => 'sriramsri1234321@gmail.com',
            'customer_phone' => '9876543210',
            'shipping_address' => '123 Test Street, Test City, 123456',
            'billing_address' => '123 Test Street, Test City, 123456',
            'status' => Order::STATUS_PENDING,
            'total_amount' => 0, // Will be calculated
            'subtotal' => 0,
            'tax_amount' => 0,
            'shipping_amount' => 50,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'notes' => 'Test order for email integration'
        ]);

        $totalAmount = 0;

        // Add order items
        foreach ($products as $product) {
            $quantity = rand(1, 3);
            $price = $product->price ?? 100;
            $itemTotal = $price * $quantity;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'price' => $price,
                'total' => $itemTotal,
                'variant_data' => null
            ]);

            $totalAmount += $itemTotal;
        }

        // Update order totals
        $order->update([
            'subtotal' => $totalAmount,
            'total_amount' => $totalAmount + $order->shipping_amount + $order->tax_amount
        ]);

        $this->command->info("Created test order: {$order->order_number} with ID: {$order->id}");
    }
}
