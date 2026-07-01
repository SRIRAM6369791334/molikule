<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\ProductSlot;
use App\Models\ProductOrderUserAddress;

class ShiprocketService
{
    protected $baseUrl;
    protected $email;
    protected $password;
    protected $pickupLocation;
    protected $channelId;

    public function __construct()
    {
        $this->baseUrl = config('services.shiprocket.base_url');
        $this->email = config('services.shiprocket.email');
        $this->password = config('services.shiprocket.password');
        $this->pickupLocation = config('services.shiprocket.pickup_location');
        $this->channelId = config('services.shiprocket.channel_id');
    }

    /**
     * Get authentication token from Shiprocket
     * Token is cached for 9 days (expires in 10 days)
     */
    public function authenticate(): ?string
    {
        $cacheKey = 'shiprocket_token';

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = $this->baseClient()->post("{$this->baseUrl}/auth/login", [
                'email' => $this->email,
                'password' => $this->password,
            ]);

            if ($response->successful()) {
                $token = $response->json('token');
                Cache::put($cacheKey, $token, now()->addDays(9));
                return $token;
            }

            Log::error('Shiprocket authentication failed', [
                'status' => $response->status(),
                'response' => $response->json()
            ]);
            return null;

        } catch (\Exception $e) {
            Log::error('Shiprocket authentication exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    protected function client()
    {
        $token = $this->authenticate();
        if (!$token) {
            throw new \Exception('Failed to authenticate with Shiprocket');
        }
        return $this->baseClient()->withHeaders([
            'Authorization' => "Bearer {$token}",
            'Content-Type' => 'application/json',
        ]);
    }

    protected function baseClient()
    {
        return Http::acceptJson()
            ->connectTimeout(10)
            ->timeout(30)
            ->retry(2, 500, function ($exception) {
                return $exception instanceof ConnectionException;
            });
    }

    protected function submitAdhocOrder(array $orderData): array
    {
        $response = $this->client()->post("{$this->baseUrl}/orders/create/adhoc", $orderData);
        $result = $response->json() ?? [];

        if ($this->isOrderCreationSuccess($result)) {
            return [$response, $result, $orderData];
        }

        $fallbackPickupLocation = $this->extractPickupLocationFallback($result);

        if ($fallbackPickupLocation && $fallbackPickupLocation !== ($orderData['pickup_location'] ?? null)) {
            Log::warning('Shiprocket pickup location mismatch, retrying with fallback location', [
                'requested' => $orderData['pickup_location'] ?? null,
                'fallback' => $fallbackPickupLocation,
            ]);

            $orderData['pickup_location'] = $fallbackPickupLocation;
            $response = $this->client()->post("{$this->baseUrl}/orders/create/adhoc", $orderData);
            $result = $response->json() ?? [];
        }

        return [$response, $result, $orderData];
    }

    protected function isOrderCreationSuccess(array $result): bool
    {
        return !empty($result['order_id']) && !empty($result['shipment_id']);
    }

    protected function extractPickupLocationFallback(array $result): ?string
    {
        $message = strtolower((string) ($result['message'] ?? ''));

        if (!str_contains($message, 'wrong pickup location')) {
            return null;
        }

        return data_get($result, 'data.data.0.pickup_location');
    }

    /**
     * Create order in Shiprocket
     */
    public function createOrder(Order $order, ?int $courierId = null): array
    {
        try {
            // Get order items (product slots)
            $orderItems = ProductSlot::where('order_id', $order->order_number)->get();
            
            if ($orderItems->isEmpty()) {
                throw new \Exception('No order items found for order: ' . $order->order_number);
            }

            // Get addresses
            $billingAddress = ProductOrderUserAddress::where('order_id', $order->order_number)
                ->where('address_type_id', 1)
                ->first();
            
            $shippingAddress = ProductOrderUserAddress::where('order_id', $order->order_number)
                ->where('address_type_id', 2)
                ->first() ?: $billingAddress;

            if (!$billingAddress) {
                throw new \Exception('Billing address not found for order: ' . $order->order_number);
            }

            // Prepare order items array
            $shiprocketItems = [];
            foreach ($orderItems as $item) {
                $shiprocketItems[] = [
                    'name' => $item->product_name ?? 'Product',
                    'sku' => ($item->product_id ?? 'PROD') . '-' . ($item->product_varient_id ?? '0'),
                    'units' => (int) $item->quantity,
                    'selling_price' => (float) $item->product_rate,
                    'discount' => (float) ($item->discount ?? 0),
                    'tax' => (float) ($item->gst_amt ?? 0),
                    'hsn' => '',
                ];
            }

            // Weight calculation - matching reference exactly
            $totalWeight = 0;
            foreach ($orderItems as $item) {
                $itemWeight = 0.3; // Default
                if ($item->product_varient_id) {
                    $variant = \App\Models\ProductVariant::find($item->product_varient_id);
                    if ($variant && $variant->weight > 0) $itemWeight = (float) $variant->weight;
                }
                if ($itemWeight == 0.3 && $item->product_id) {
                    $product = \App\Models\Product::find($item->product_id);
                    if ($product && $product->weight > 0) $itemWeight = (float) $product->weight;
                }
                $totalWeight += $itemWeight * (int) $item->quantity;
            }
            $weight = max(0.5, $totalWeight);

            $orderData = [
                'order_id' => $order->order_number,
                'order_date' => $order->created_at->format('Y-m-d H:i'),
                'pickup_location' => $this->pickupLocation,
                'comment' => $order->order_notes ?? '',
                'billing_customer_name' => $order->customer_name ?: 'Customer',
                'billing_last_name' => '',
                'billing_address' => $billingAddress->address_line_one ?? '',
                'billing_address_2' => $billingAddress->address_line_two ?? '',
                'billing_city' => $billingAddress->city ?? '',
                'billing_pincode' => (string) ($billingAddress->pincode ?? ''),
                'billing_state' => $billingAddress->state ?? '',
                'billing_country' => 'India',
                'billing_email' => $order->customer_email ?: 'customer@example.com',
                'billing_phone' => $billingAddress->address_phone_number ?: $order->customer_phone,
                'shipping_is_billing' => $shippingAddress->id === $billingAddress->id,
                'order_items' => $shiprocketItems,
                'payment_method' => $order->payment_method === 'cod' ? 'COD' : 'Prepaid',
                'shipping_charges' => (float) $order->shipping_cost,
                'sub_total' => (float) ($order->total_amount - $order->shipping_cost),
                'length' => 25,
                'breadth' => 20,
                'height' => max(5, $orderItems->sum('quantity') * 3),
                'weight' => $weight,
            ];

            if ($shippingAddress->id !== $billingAddress->id) {
                $orderData['shipping_customer_name'] = $shippingAddress->address_username ?: $order->customer_name;
                $orderData['shipping_last_name'] = '';
                $orderData['shipping_address'] = $shippingAddress->address_line_one ?? '';
                $orderData['shipping_city'] = $shippingAddress->city ?? '';
                $orderData['shipping_pincode'] = (string) ($shippingAddress->pincode ?: $billingAddress->pincode);
                $orderData['shipping_state'] = $shippingAddress->state ?? '';
                $orderData['shipping_country'] = 'India';
                $orderData['shipping_email'] = $order->customer_email;
                $orderData['shipping_phone'] = $shippingAddress->address_phone_number ?: $order->customer_phone;
            }

            if ($this->channelId) $orderData['channel_id'] = $this->channelId;

            [$response, $result, $orderData] = $this->submitAdhocOrder($orderData);

            if ($this->isOrderCreationSuccess($result)) {
                $order->update([
                    'shiprocket_order_id' => $result['order_id'] ?? null,
                    'shiprocket_shipping_id' => $result['shipment_id'] ?? null,
                ]);

                $returnData = [
                    'success' => true,
                    'shiprocket_order_id' => $result['order_id'] ?? null,
                    'shipment_id' => $result['shipment_id'] ?? null,
                    'message' => 'Order created successfully in Shiprocket'
                ];

                if (isset($result['shipment_id'])) {
                    $resolvedCourierId = is_numeric($courierId) && (int) $courierId > 0 ? (int) $courierId : null;
                    $awbResult = $this->generateAWB((int) $result['shipment_id'], $resolvedCourierId);
                    if ($awbResult['success']) {
                        $order->update(['awb_code' => $awbResult['awb_code']]);
                        $returnData['awb_code'] = $awbResult['awb_code'];
                        $returnData['courier_name'] = $awbResult['courier_name'] ?? null;
                    } else {
                        Log::warning('Shiprocket AWB generation failed', [
                            'order_number' => $order->order_number,
                            'shipment_id' => $result['shipment_id'],
                            'courier_id' => $resolvedCourierId,
                            'message' => $awbResult['message'] ?? 'Unknown AWB error',
                        ]);
                    }
                }
                return $returnData;
            }

            Log::error('Shiprocket order creation failed', [
                'order_number' => $order->order_number,
                'pickup_location' => $orderData['pickup_location'] ?? null,
                'status' => $response->status(),
                'response' => $result,
            ]);

            return ['success' => false, 'message' => $result['message'] ?? 'Failed to create Shiprocket order'];

        } catch (\Exception $e) {
            Log::error('Shiprocket order creation exception', ['message' => $e->getMessage()]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getAvailableCouriers(string $pickupPincode, string $deliveryPincode, float $codAmount = 0, float $weight = 0.5): array
    {
        try {
            $response = $this->client()->get("{$this->baseUrl}/courier/serviceability/", [
                'pickup_postcode' => $pickupPincode,
                'delivery_postcode' => $deliveryPincode,
                'cod' => $codAmount > 0 ? 1 : 0,
                'weight' => $weight,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'couriers' => $response->json('data.available_courier_companies') ?? []
                ];
            }
            return ['success' => false, 'message' => 'Failed to fetch couriers', 'couriers' => []];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage(), 'couriers' => []];
        }
    }

    public function generateAWB(int $shipmentId, ?int $courierId = null): array
    {
        try {
            $data = ['shipment_id' => $shipmentId];
            if ($courierId) $data['courier_id'] = $courierId;

            $response = $this->client()->post("{$this->baseUrl}/courier/assign/awb", $data);
            $result = $response->json();

            if ($response->successful()) {
                $awbCode = $result['response']['data']['awb_code'] ?? $result['data']['awb_code'] ?? $result['awb_code'] ?? null;
                if ($awbCode) {
                    return ['success' => true, 'awb_code' => $awbCode, 'courier_name' => $result['response']['data']['courier_name'] ?? null];
                }
            }
            return ['success' => false, 'message' => $result['message'] ?? 'Failed to generate AWB'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getTracking(string $awbCode): array
    {
        try {
            $response = $this->client()->get("{$this->baseUrl}/courier/track/awb/{$awbCode}");
            if ($response->successful()) {
                return ['success' => true, 'tracking' => $response->json('tracking_data')];
            }
            return ['success' => false, 'message' => 'Failed to fetch tracking'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function generateLabel(int $shipmentId): array
    {
        try {
            $response = $this->client()->post("{$this->baseUrl}/courier/generate/label", ['shipment_id' => [$shipmentId]]);
            if ($response->successful()) {
                return ['success' => true, 'label_url' => $response->json('label_url')];
            }
            return ['success' => false, 'message' => 'Failed to generate label'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function generateInvoice(int $orderId): array
    {
        try {
            $response = $this->client()->post("{$this->baseUrl}/orders/print/invoice", ['ids' => [$orderId]]);
            if ($response->successful()) {
                return ['success' => true, 'invoice_url' => $response->json('invoice_url')];
            }
            return ['success' => false, 'message' => 'Failed to generate invoice'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Cancel a Shiprocket order
     */
    public function cancelOrder(int $shiprocketOrderId): array
    {
        try {
            $response = $this->client()->post("{$this->baseUrl}/orders/cancel", [
                'ids' => [$shiprocketOrderId]
            ]);

            if ($response->successful()) {
                return ['success' => true, 'message' => 'Order cancelled successfully'];
            }

            return ['success' => false, 'message' => $response->json('message') ?? 'Failed to cancel order'];
        } catch (\Exception $e) {
            Log::error('Shiprocket cancel order exception', ['message' => $e->getMessage()]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get tracking by order ID
     */
    public function getTrackingByOrderId(string $orderId): array
    {
        try {
            $response = $this->client()->get("{$this->baseUrl}/courier/track", ['order_id' => $orderId]);
            if ($response->successful()) {
                return ['success' => true, 'tracking' => $response->json()];
            }
            return ['success' => false, 'message' => 'Failed to fetch tracking information'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
