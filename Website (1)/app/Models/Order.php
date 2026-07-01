<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'pincode',
        'shipping_address',
        'billing_address',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'discount_amount',
        'coupon_code',
        'coupon_discount',
        'notes',
        'dispatch_date',
        'delivery_date',
        'tracking_number',
        'version',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'shiprocket_order_id',
        'shiprocket_shipping_id',
        'awb_code',
        'courier_name',
        'courier_id',
        'label_url',
        'invoice_url',
        'shipping_cost',
        'order_notes'
    ];

    protected $attributes = [
        'version' => 1,
        'status' => 'pending',
        'payment_status' => 'pending',
        'payment_method' => 'cash',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'coupon_discount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'dispatch_date' => 'datetime',
        'delivery_date' => 'datetime',
        'version' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Status workflow constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_DISPATCH = 'dispatch';
    const STATUS_DELIVERY = 'delivered';

    /**
     * Relationship with user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship with order items
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Alias for orderItems to maintain compatibility with some existing views if any
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Status history
     */
    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id');
    }

    /**
     * Generate a unique order number for the Molikule Enterprise
     */
    public static function generateOrderNumber()
    {
        $prefix = 'MOL';
        $year = date('Y');
        $timePart = strtoupper(dechex(time()));
        $randomPart = strtoupper(bin2hex(random_bytes(2)));
        
        return "{$prefix}-{$year}-{$timePart}{$randomPart}";
    }

    /**
     * Formatted total amount
     */
    public function getFormattedTotalAttribute()
    {
        return '₹' . number_format((float)$this->total_amount, 2);
    }

    /**
     * Get total number of items
     */
    public function getTotalItemsAttribute()
    {
        return $this->orderItems()->sum('quantity');
    }

    const STATUS_LABELS = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_PROCESSING => 'Processing',
        self::STATUS_DISPATCH => 'Dispatched',
        self::STATUS_DELIVERY => 'Delivered',
    ];

    /**
     * Get user-friendly status label
     */
    public function getStatusLabelAttribute()
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);
    }
}
