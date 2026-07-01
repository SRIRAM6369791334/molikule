<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class BroadcastOrderStatusUpdate implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderStatusUpdated $event): void
    {
        // The event is already configured to broadcast, so we just need to log it
        Log::info("Broadcasting order status update for order {$event->order->order_number}: {$event->oldStatus} -> {$event->newStatus}");

        // Additional processing can be added here if needed
        // For example, caching, additional notifications, etc.
    }

    /**
     * Handle failed broadcast attempts.
     */
    public function failed(OrderStatusUpdated $event, \Throwable $exception): void
    {
        Log::error("Failed to broadcast order status update for order {$event->order->order_number}: " . $exception->getMessage());
    }
}
