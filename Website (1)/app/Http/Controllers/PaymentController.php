<?php

namespace App\Http\Controllers;

use App\Models\ProductTracking;
use App\Models\Order;
use App\Services\ShiprocketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    private $razorpay;

    public function __construct($razorpay = null)
    {
        $this->razorpay = $razorpay ?: new Api(
            config('services.razorpay.key_id'),
            config('services.razorpay.key_secret')
        );
    }

    /**
     * Create Razorpay order
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
            'amount' => 'required|numeric|min:1'
        ]);

        try {
            $orderData = [
                'receipt' => $request->order_number,
                'amount' => (int) round($request->amount * 100),
                'currency' => 'INR',
                'payment_capture' => 1,
                'notes' => [
                    'order_number' => $request->order_number,
                ],
            ];

            $razorpayOrder = $this->attemptGatewayCall(
                fn () => $this->razorpay->order->create($orderData)
            );
            $razorpayOrderData = $this->entityToArray($razorpayOrder);
            $razorpayOrderId = data_get($razorpayOrderData, 'id');

            if (!$razorpayOrderId) {
                throw new \RuntimeException('Razorpay did not return an order id.');
            }

            Order::where('order_number', $request->order_number)
                ->update(['razorpay_order_id' => $razorpayOrderId]);

            Log::info('Razorpay order created', [
                'order_number' => $request->order_number,
                'razorpay_order_id' => $razorpayOrderId,
                'amount' => $request->amount
            ]);

            return response()->json([
                'id' => $razorpayOrderId,
                'amount' => data_get($razorpayOrderData, 'amount', $orderData['amount']),
                'currency' => data_get($razorpayOrderData, 'currency', $orderData['currency']),
                'receipt' => data_get($razorpayOrderData, 'receipt', $orderData['receipt']),
                'notes' => data_get($razorpayOrderData, 'notes', $orderData['notes']),
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay order creation failed', [
                'order_number' => $request->order_number,
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Failed to create payment order'], 500);
        }
    }

    /**
     * Verify payment signature
     */
    public function verifyPayment(Request $request)
    {
        Log::info('Razorpay Verify Incoming', $request->all());
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'nullable|string',
            'razorpay_signature' => 'nullable|string',
            'order_number' => 'nullable|string'
        ]);

        try {
            $paymentEntity = null;
            $paymentData = [];
            $resolvedOrderId = $request->input('razorpay_order_id') ?: null;
            $order = null;

            if ($resolvedOrderId) {
                $order = Order::where('razorpay_order_id', $resolvedOrderId)->first();
            }

            if (!$order && $request->filled('order_number')) {
                $order = Order::where('order_number', $request->order_number)->first();
                $resolvedOrderId = $resolvedOrderId ?: ($order?->razorpay_order_id ?: null);
            }

            if (!$order) {
                return response()->json(['status' => 'failed', 'message' => 'Order not found'], 404);
            }

            if ($request->filled('razorpay_signature') && $request->input('razorpay_signature') !== '') {
                if (!$resolvedOrderId) {
                    throw new \RuntimeException('Unable to resolve Razorpay order id for this payment.');
                }

                $attributes = [
                    'razorpay_order_id' => $resolvedOrderId,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature
                ];

                $this->razorpay->utility->verifyPaymentSignature($attributes);
            } else {
                $paymentEntity = $this->attemptGatewayCall(
                    fn () => $this->razorpay->payment->fetch($request->razorpay_payment_id)
                );
                $paymentData = $this->entityToArray($paymentEntity);
                $entityOrderId = data_get($paymentData, 'order_id');
                $paymentStatus = data_get($paymentData, 'status');

                if ($entityOrderId && $entityOrderId !== $resolvedOrderId) {
                    Log::warning('Razorpay order_id mismatch: trusting payment entity', [
                        'resolved' => $resolvedOrderId,
                        'entity' => $entityOrderId,
                    ]);

                    $resolvedOrderId = $entityOrderId;
                    $order = Order::where('razorpay_order_id', $resolvedOrderId)->first()
                        ?? Order::where('order_number', $request->order_number)->first();

                    if (!$order) {
                        throw new \RuntimeException('Order not found after re-resolving from payment entity.');
                    }
                } elseif (!$entityOrderId) {
                    $this->assertStandalonePaymentMatchesOrder($paymentData, $order);

                    Log::warning('Razorpay payment has no order_id, verified via metadata fallback', [
                        'order_number' => $order->order_number,
                        'razorpay_payment_id' => $request->razorpay_payment_id,
                        'amount' => data_get($paymentData, 'amount'),
                        'description' => data_get($paymentData, 'description'),
                    ]);
                }

                if (!in_array($paymentStatus, ['authorized', 'captured'], true)) {
                    throw new \RuntimeException('Payment has not been authorized by Razorpay.');
                }

                if (!$entityOrderId && $paymentStatus === 'authorized') {
                    $capturedPayment = $this->captureStandalonePayment(
                        $paymentEntity,
                        $order,
                        $request->razorpay_payment_id
                    );
                    $paymentData = $this->entityToArray($capturedPayment);
                }

                Log::warning('Razorpay signature missing, verified via payment fetch fallback', [
                    'order_number' => $order->order_number,
                    'razorpay_order_id' => $resolvedOrderId,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'payment_order_id' => $entityOrderId,
                ]);
            }

            $order->update([
                'razorpay_order_id' => $resolvedOrderId ?: $order->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
                'payment_status' => 'paid'
            ]);

            $order = $order->fresh();
            $this->syncShiprocketOrder($order);

            session(['order_number' => $order->order_number]);
            session()->forget('cart');

            Log::info('Payment verified and order updated', [
                'order_number' => $order->order_number,
                'razorpay_order_id' => $resolvedOrderId ?: $order->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id
            ]);

            // Send Notifications
            $this->sendOrderNotifications($order);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Payment verification failed', [
                'order_number' => $request->order_number,
                'razorpay_order_id' => $request->razorpay_order_id,
                'error' => $e->getMessage()
            ]);

            return response()->json(['status' => 'failed', 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Handle Razorpay webhooks
     */
    public function handleWebhook(Request $request)
    {
        try {
            $webhookSecret = config('services.razorpay.webhook_secret');
            $signature = $request->header('X-Razorpay-Signature');

            $expectedSignature = hash_hmac('sha256', $request->getContent(), $webhookSecret);

            if (!hash_equals($expectedSignature, (string) $signature)) {
                return response()->json(['status' => 'invalid_signature'], 400);
            }

            $event = $request->event;
            $paymentEntity = $request->payload['payment']['entity'];

            switch ($event) {
                case 'payment.captured':
                    Order::where('razorpay_payment_id', $paymentEntity['id'])
                        ->update(['payment_status' => 'paid']);
                    break;
                case 'payment.failed':
                    Order::where('razorpay_payment_id', $paymentEntity['id'])
                        ->update(['payment_status' => 'failed']);
                    break;
            }

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            Log::error('Webhook processing failed', ['error' => $e->getMessage()]);

            return response()->json(['status' => 'error'], 500);
        }
    }

    private function entityToArray($entity): array
    {
        if (is_array($entity)) {
            return $entity;
        }

        if (is_object($entity) && method_exists($entity, 'toArray')) {
            $data = $entity->toArray();
            if (is_array($data)) {
                return $data;
            }
        }

        if (is_object($entity)) {
            return get_object_vars($entity);
        }

        return [];
    }

    private function assertStandalonePaymentMatchesOrder(array $paymentData, Order $order): void
    {
        $paymentAmount = (int) data_get($paymentData, 'amount', 0);
        $expectedAmount = $this->expectedPaymentAmount($order);

        if ($paymentAmount !== $expectedAmount) {
            throw new \RuntimeException('Payment amount does not match the order total.');
        }

        $noteOrderNumber = trim((string) data_get($paymentData, 'notes.order_number', ''));
        $paymentDescription = trim((string) data_get($paymentData, 'description', ''));
        $expectedDescription = 'Order #' . $order->order_number;

        if ($noteOrderNumber !== '' && $noteOrderNumber !== $order->order_number) {
            throw new \RuntimeException('Payment metadata does not match the expected order.');
        }

        if ($noteOrderNumber === '' && $paymentDescription !== $expectedDescription) {
            throw new \RuntimeException('Payment metadata does not match the expected order.');
        }

        $paymentEmail = strtolower(trim((string) data_get($paymentData, 'email', '')));
        $orderEmail = strtolower(trim((string) ($order->customer_email ?? '')));

        if ($paymentEmail !== '' && $orderEmail !== '' && $paymentEmail !== $orderEmail) {
            throw new \RuntimeException('Payment email does not match the order.');
        }

        $paymentPhone = $this->normalizePhone(data_get($paymentData, 'contact'));
        $orderPhone = $this->normalizePhone($order->customer_phone);

        if ($paymentPhone !== '' && $orderPhone !== '' && $paymentPhone !== $orderPhone) {
            throw new \RuntimeException('Payment contact does not match the order.');
        }
    }

    private function captureStandalonePayment($paymentEntity, Order $order, string $paymentId)
    {
        if (!is_object($paymentEntity) || !method_exists($paymentEntity, 'capture')) {
            throw new \RuntimeException('Payment requires capture but Razorpay capture is unavailable.');
        }

        $capturedPayment = $this->attemptGatewayCall(fn () => $paymentEntity->capture([
            'amount' => $this->expectedPaymentAmount($order),
            'currency' => 'INR',
        ]));

        Log::info('Razorpay payment captured via fallback', [
            'order_number' => $order->order_number,
            'razorpay_payment_id' => $paymentId,
        ]);

        return $capturedPayment;
    }

    private function expectedPaymentAmount(Order $order): int
    {
        return (int) round((float) $order->total_amount * 100);
    }

    private function normalizePhone(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);

        if (strlen($digits) > 10) {
            $digits = substr($digits, -10);
        }

        return $digits;
    }

    private function attemptGatewayCall(callable $callback, int $maxAttempts = 3, int $sleepMs = 300)
    {
        $attempt = 0;

        while (true) {
            try {
                return $callback();
            } catch (\Throwable $e) {
                $attempt++;

                if ($attempt >= $maxAttempts || !$this->isRetriableGatewayException($e)) {
                    throw $e;
                }

                usleep($sleepMs * 1000);
            }
        }
    }

    private function isRetriableGatewayException(\Throwable $e): bool
    {
        $message = strtolower($e->getMessage());

        return str_contains($message, 'curl error 28')
            || str_contains($message, 'curl error 7')
            || str_contains($message, 'ssl connection timeout')
            || str_contains($message, 'timed out');
    }

    protected function resolveOrderCourierId(Order $order): ?int
    {
        if (!is_numeric($order->courier_id ?? null)) {
            return null;
        }

        $courierId = (int) $order->courier_id;

        return $courierId > 0 ? $courierId : null;
    }

    protected function syncShiprocketOrder(Order $order): void
    {
        if ($order->payment_status !== 'paid' || $order->shiprocket_order_id) {
            return;
        }

        try {
            $result = app(ShiprocketService::class)->createOrder($order, $this->resolveOrderCourierId($order));

            if (!($result['success'] ?? false)) {
                Log::error('Shiprocket sync failed after payment verification', [
                    'order_number' => $order->order_number,
                    'message' => $result['message'] ?? 'Unknown Shiprocket error',
                ]);
                return;
            }

            ProductTracking::syncFromOrder($order->fresh());

            Log::info('Shiprocket order synced after payment verification', [
                'order_number' => $order->order_number,
                'shiprocket_order_id' => $result['shiprocket_order_id'] ?? null,
                'shipment_id' => $result['shipment_id'] ?? null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Shiprocket sync exception after payment verification', [
                'order_number' => $order->order_number,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send order notifications to customer and admin.
     */
    public function sendOrderNotifications(Order $order)
    {
        try {
            // 1. Send to Customer
            Mail::send('emails.order-confirmation', ['order' => $order], function($message) use ($order) {
                $message->to($order->customer_email);
                $message->subject('Order Confirmation - #' . $order->order_number . ' | Molikule');
            });

            // 2. Send to Admin
            $adminEmail = env('ADMIN_EMAIL');
            if ($adminEmail) {
                Mail::send('emails.admin-order-notification', ['order' => $order], function($message) use ($order, $adminEmail) {
                    $message->to($adminEmail);
                    $message->subject('NEW ORDER: #' . $order->order_number . ' (' . formatPrice($order->total_amount) . ')');
                });
            }
        } catch (\Exception $e) {
            Log::error("Order notification failed for #{$order->order_number}: " . $e->getMessage());
        }
    }
}
