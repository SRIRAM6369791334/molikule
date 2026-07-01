<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ProductTracking extends Model
{
    protected $table = 'product_tracking';

    protected $fillable = [
        'user_id',
        'order_id',
        'delivery_status',
        'status',
        'channel_id',
        'shiprocket_order_id',
        'shiprocket_shipment_id',
        'awb_code',
        'tracking_url',
        'delivered_date',
        'return_requested',
        'return_approval_date',
    ];

    protected $casts = [
        'channel_id' => 'integer',
        'return_requested' => 'integer',
        'user_id' => 'integer',
    ];

    /**
     * Create or update tracking record from Order data
     */
    public static function syncFromOrder($order, ?string $trackingUrl = null): ?self
    {
        try {
            if (!$order->shiprocket_order_id) {
                return null;
            }

            $tracking = self::updateOrCreate(
                ['order_id' => $order->order_number],
                [
                    'user_id' => $order->user_id,
                    'delivery_status' => (string) $order->status,
                    'status' => $order->status,
                    'shiprocket_order_id' => (string) $order->shiprocket_order_id,
                    'shiprocket_shipment_id' => (string) $order->shiprocket_shipping_id,
                    'awb_code' => $order->awb_code,
                    'tracking_url' => $trackingUrl,
                    'return_requested' => 0,
                ]
            );

            return $tracking;

        } catch (\Exception $e) {
            Log::error('ProductTracking: Sync failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_number');
    }
}
