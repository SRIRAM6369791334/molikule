<?php

namespace App\Jobs;

use App\Events\OrderStatusUpdated;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class UpdateOrderStatus implements ShouldQueue
{
    use Queueable;

    protected $orderId;
    protected $newStatus;
    protected $changedBy;
    protected $notes;
    protected $metadata;

    /**
     * Number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Number of seconds to wait before retrying the job.
     */
    public int $timeout = 300;

    /**
     * Get the backoff strategy for this job.
     * Retry after 60, 120, and 300 seconds.
     */
    public function backoff(): array
    {
        return [60, 120, 300];
    }

    /**
     * Create a new job instance.
     */
    public function __construct(int $orderId, string $newStatus, ?int $changedBy = null, ?string $notes = null, ?array $metadata = [])
    {
        $this->orderId = $orderId;
        $this->newStatus = $newStatus;
        $this->changedBy = $changedBy;
        $this->notes = $notes;
        $this->metadata = $metadata;
        $this->queue = 'orders'; // Assign to specific queue for priority
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $order = Order::find($this->orderId);

            if (!$order) {
                Log::error("Order not found for status update job: {$this->orderId}");
                return;
            }

            Log::info("Processing queued status update for order {$order->order_number}: {$order->status} -> {$this->newStatus}");

            // Validate status transition
            if (!$order->canTransitionTo($this->newStatus)) {
                Log::warning("Invalid status transition in job for order {$order->order_number}: {$order->status} -> {$this->newStatus}");
                return;
            }

            // Store old status for event
            $oldStatus = $order->status;

            // Perform the status transition
            if (!$order->transitionTo($this->newStatus, $this->changedBy, $this->notes, $this->metadata)) {
                Log::error("Failed to transition order {$order->order_number} to status {$this->newStatus}");
                return;
            }

            Log::info("Status transition successful for order {$order->order_number} to {$this->newStatus}");

            // Fire event - this will trigger both broadcasting and email notifications via registered listeners
            event(new OrderStatusUpdated($order, $oldStatus, $this->newStatus, $this->changedBy, $this->metadata));

        } catch (\Exception $e) {
            Log::error("Exception in UpdateOrderStatus job for order {$this->orderId}: " . $e->getMessage());
            throw $e; // Re-throw to mark job as failed
        }
    }

    /**
     * Handle job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("UpdateOrderStatus job failed for order {$this->orderId}: " . $exception->getMessage(), [
            'exception' => get_class($exception),
            'new_status' => $this->newStatus,
            'attempts' => $this->attempts(),
        ]);

        // Mark order with sync_failed flag to notify admin
        try {
            $order = Order::find($this->orderId);
            if ($order) {
                $order->update([
                    'sync_failed' => true,
                    'last_sync_error' => $exception->getMessage(),
                    'last_sync_error_at' => now(),
                ]);

                Log::info("Order {$this->orderId} marked as sync_failed");
            }
        } catch (\Exception $e) {
            Log::error("Failed to mark order as sync_failed: " . $e->getMessage());
        }
    }
}
