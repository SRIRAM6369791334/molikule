<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use App\Mail\OrderDelivered;
use App\Mail\OrderDispatched;
use App\Mail\OrderPending;
use App\Mail\OrderProcessing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderStatusUpdatedListener implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Handle the event.
     */
    public function handle(OrderStatusUpdated $event)
    {
        Log::info("Handling OrderStatusUpdated event for order ID: {$event->order->id}");

        $order = $event->order;
        $status = $order->status;
        $customerEmail = $order->customer_email;

        Log::info("Order status: {$status}, Customer email: {$customerEmail}");

        if (!filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
            Log::warning("Invalid customer email address: {$customerEmail} for order {$order->order_number}. Skipping customer email.");
            return;
        }

        switch ($status) {
            case 'pending':
                try {
                    Log::info("Sending OrderPending email to {$customerEmail}");
                    Mail::to($customerEmail)->send(new OrderPending($order));
                    Log::info("OrderPending email sent to {$customerEmail}");
                } catch (\Exception $e) {
                    Log::error("Failed to send OrderPending email to {$customerEmail}: " . $e->getMessage());
                }
                break;
            case 'processing':
                try {
                    Log::info("Sending OrderProcessing email to {$customerEmail}");
                    Mail::to($customerEmail)->send(new OrderProcessing($order));
                    Log::info("OrderProcessing email sent to {$customerEmail}");
                } catch (\Exception $e) {
                    Log::error("Failed to send OrderProcessing email to {$customerEmail}: " . $e->getMessage());
                }
                break;
            case 'dispatch':
                try {
                    Log::info("Sending OrderDispatched email to {$customerEmail}");
                    Mail::to($customerEmail)->send(new OrderDispatched($order));
                    Log::info("OrderDispatched email sent to {$customerEmail}");
                } catch (\Exception $e) {
                    Log::error("Failed to send OrderDispatched email to {$customerEmail}: " . $e->getMessage());
                }
                break;
            case 'delivered':
                try {
                    Log::info("Sending OrderDelivered email to {$customerEmail}");
                    Mail::to($customerEmail)->send(new OrderDelivered($order));
                    Log::info("OrderDelivered email sent to {$customerEmail}");
                } catch (\Exception $e) {
                    Log::error("Failed to send OrderDelivered email to {$customerEmail}: " . $e->getMessage());
                }
                break;
            default:
                Log::warning('No email sent for status: ' . $status);
                break;
        }
    }
}
