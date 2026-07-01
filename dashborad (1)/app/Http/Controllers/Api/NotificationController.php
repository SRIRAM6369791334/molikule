<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPending;
use App\Mail\OrderProcessing;
use App\Mail\OrderDispatched;
use App\Mail\OrderDelivered;

class NotificationController extends Controller
{
    public function sendOrderEmails(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'order_id' => 'required|integer|exists:orders,id',
                'source' => 'nullable|string|max:255'
            ]);

            // Find the order with relationships
            $order = Order::with('orderItems')->findOrFail($validatedData['order_id']);

            Log::info('Email notification triggered for order', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'source' => $validatedData['source'] ?? 'unknown'
            ]);

            // Send emails using existing logic
            $this->sendOrderConfirmation($order);
            $this->sendAdminNotifications($order);

            return response()->json([
                'success' => true,
                'message' => 'Email notifications sent successfully',
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send email notifications', [
                'order_id' => $request->input('order_id'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send email notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send order confirmation email to customer
     */
    private function sendOrderConfirmation($order)
    {
        try {
            if (filter_var($order->customer_email, FILTER_VALIDATE_EMAIL)) {
                $mailClass = $this->getMailClassForStatus($order->status);
                if ($mailClass) {
                    Mail::to($order->customer_email)->send(new $mailClass($order));
                    Log::info("Customer confirmation email sent for order {$order->order_number}");
                }
            } else {
                Log::warning("Invalid customer email for order {$order->order_number}: {$order->customer_email}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to send customer email for order {$order->order_number}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send notification email to admin
     */
    private function sendAdminNotifications($order)
    {
        try {
            $adminEmail = env('ADMIN_EMAIL');
            if (filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                $mailClass = $this->getMailClassForStatus($order->status);
                if ($mailClass) {
                    Mail::to($adminEmail)->send(new $mailClass($order));
                    Log::info("Admin notification email sent for order {$order->order_number}");
                }
            } else {
                Log::warning("Invalid or missing admin email: {$adminEmail}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to send admin email for order {$order->order_number}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get the appropriate mail class based on order status
     */
    private function getMailClassForStatus($status)
    {
        switch ($status) {
            case Order::STATUS_PENDING:
                return OrderPending::class;
            case Order::STATUS_PROCESSING:
                return OrderProcessing::class;
            case Order::STATUS_DISPATCH:
                return OrderDispatched::class;
            case Order::STATUS_DELIVERY:
                return OrderDelivered::class;
            default:
                Log::warning("No mail class defined for status: {$status}");
                return null;
        }
    }
}
