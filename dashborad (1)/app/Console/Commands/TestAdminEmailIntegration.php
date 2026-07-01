<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\AdminApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestAdminEmailIntegration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:test-email-integration {order_id?} {--test-api : Test API connection only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the email integration between frontend and admin dashboard';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->argument('order_id');
        $testApiOnly = $this->option('test-api');

        $this->info('🔧 Testing Admin Email Integration');
        $this->line('================================');

        // Test API connection first
        $this->testApiConnection();

        if ($testApiOnly) {
            return;
        }

        // If no order ID provided, find the latest order
        if (!$orderId) {
            $order = Order::latest()->first();
            if (!$order) {
                $this->error('❌ No orders found in database');
                return;
            }
            $orderId = $order->id;
            $this->warn("⚠️  No order ID provided, using latest order: {$orderId}");
        }

        // Find the order
        $order = Order::with('orderItems')->find($orderId);
        if (!$order) {
            $this->error("❌ Order with ID {$orderId} not found");
            return;
        }

        $this->info("📦 Order Details:");
        $this->line("   Order Number: {$order->order_number}");
        $this->line("   Customer: {$order->customer_name} ({$order->customer_email})");
        $this->line("   Status: {$order->status}");
        $this->line("   Total: ₹{$order->total_amount}");
        $this->line("   Items: {$order->orderItems->count()}");
        $this->newLine();

        // Test the API call
        $this->info('🚀 Testing API Call...');
        $adminApiService = new AdminApiService();

        try {
            $result = $adminApiService->triggerOrderEmails($orderId, 'test-command');

            if ($result['success']) {
                $this->info('✅ API Call Successful!');
                $this->line("   Response: {$result['message']}");
                $this->line("   Order ID: {$result['order_id']}");
            } else {
                $this->error('❌ API Call Failed!');
                $this->line("   Error: {$result['message']}");
                if (isset($result['error'])) {
                    $this->line("   Details: {$result['error']}");
                }
            }
        } catch (\Exception $e) {
            $this->error('❌ Exception during API call:');
            $this->line("   {$e->getMessage()}");
            Log::error('Test command API call failed', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
        }

        $this->newLine();
        $this->info('📋 Next Steps:');
        $this->line('   1. Check admin dashboard logs for email sending');
        $this->line('   2. Verify emails were sent to customer and admin');
        $this->line('   3. Test with real frontend order placement');
    }

    /**
     * Test API connection to admin dashboard
     */
    private function testApiConnection()
    {
        $this->info('🔗 Testing API Connection...');

        $adminApiService = new AdminApiService();
        $baseUrl = $adminApiService->getBaseUrl();

        // If this is the admin dashboard itself, test the local API
        if ($baseUrl === 'http://127.0.0.1:8001') {
            $this->info('ℹ️  This appears to be the admin dashboard project itself');
            $this->info('🔧 Testing local API endpoint...');

            // Check if there are any orders in the database
            $orderCount = \App\Models\Order::count();

            if ($orderCount === 0) {
                $this->warn('⚠️  No orders found in database');
                $this->info('ℹ️  To test the email integration, you need to:');
                $this->line('   1. Create some test orders in the database');
                $this->line('   2. Or place a real order through the frontend');
                $this->line('   3. Then run this test command again');
                return;
            }

            // Use the first available order for testing
            $order = \App\Models\Order::first();

            try {
                // Test the API endpoint directly
                $controller = new \App\Http\Controllers\Api\NotificationController();
                $request = new \Illuminate\Http\Request();
                $request->merge(['order_id' => $order->id, 'source' => 'test-connection']);

                $response = $controller->sendOrderEmails($request);
                $data = $response->getData();

                if ($data->success) {
                    $this->info('✅ Local API Test Successful!');
                    $this->line("   Response: {$data->message}");
                    $this->line("   Test Order: {$order->order_number} (ID: {$order->id})");
                } else {
                    $this->error('❌ Local API Test Failed!');
                    $this->line("   Error: {$data->message}");
                }
            } catch (\Exception $e) {
                $this->error('❌ Local API Test Exception!');
                $this->line("   Error: {$e->getMessage()}");
            }
        } else {
            // Test external connection
            $connectionTest = $adminApiService->testConnection();

            if ($connectionTest['success']) {
                $this->info('✅ Connection Successful!');
                $this->line("   URL: {$connectionTest['url']}");
                $this->line("   Status: {$connectionTest['status']}");
            } else {
                $this->error('❌ Connection Failed!');
                $this->line("   URL: {$connectionTest['url']}");
                $this->line("   Error: " . (isset($connectionTest['error']) ? $connectionTest['error'] : 'Unknown error'));
                $this->warn('⚠️  Make sure admin dashboard is running on the configured URL');
            }
        }

        $this->newLine();
    }
}
