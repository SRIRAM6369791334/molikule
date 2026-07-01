<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

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
        'label_url',
        'invoice_url',
        'shipping_cost',
        'order_notes',
        'payment_method',
        'payment_status',
        'discount_amount',
        'coupon_code',
        'coupon_discount'
    ];

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


    protected $attributes = [
        'version' => 1,
    ];

    protected $appends = [
        'status_progress',
        'status_progress_class',
        'formatted_total',
        'status_label'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'coupon_discount' => 'decimal:2',
        'dispatch_date' => 'datetime',
        'delivery_date' => 'datetime',
        'version' => 'integer',
    ];


    // Status workflow constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_DISPATCH = 'dispatch';
    const STATUS_DELIVERY = 'delivered';

    const STATUS_COLORS = [
        self::STATUS_PENDING => 'warning',
        self::STATUS_PROCESSING => 'secondary',
        self::STATUS_DISPATCH => 'info',
        self::STATUS_DELIVERY => 'success',
    ];

    const STATUS_LABELS = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_PROCESSING => 'Processing',
        self::STATUS_DISPATCH => 'Dispatched',
        self::STATUS_DELIVERY => 'Delivered',
    ];

    const STATUS_TRANSITIONS = [
        self::STATUS_PENDING => [self::STATUS_PROCESSING],
        self::STATUS_PROCESSING => [self::STATUS_DISPATCH],
        self::STATUS_DISPATCH => [self::STATUS_DELIVERY],
        self::STATUS_DELIVERY => [], // Final state
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id');
    }

    public function couponUsage()
    {
        return $this->hasOne(CouponUsage::class, 'order_id');
    }


    // Get total number of items
    public function getTotalItems()
    {
        return $this->orderItems()->sum('quantity');
    }

    // Calculate and get total items (including child relations)
    public function getTotalItemsAttribute()
    {
        return $this->getTotalItems();
    }

    // Get formatted total amount
    public function getFormattedTotalAttribute()
    {
        return '₹' . number_format($this->total_amount, 2);
    }

    // Get formatted order date
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M, Y');
    }

    // Get formatted dispatch date
    public function getFormattedDispatchDateAttribute()
    {
        return $this->dispatch_date?->format('d M, Y H:i');
    }

    // Get formatted delivery date
    public function getFormattedDeliveryDateAttribute()
    {
        return $this->delivery_date?->format('d M, Y H:i');
    }

    // Status badge accessor
    public function getStatusBadgeAttribute()
    {
        $color = self::STATUS_COLORS[$this->status] ?? 'secondary';
        $label = self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);

        return "<span class=\"badge bg-{$color} rounded-pill px-3\"><i class=\"bx bx-package me-1\"></i>{$label}</span>";
    }

    // Status workflow methods
    public function canTransitionTo(string $newStatus): bool
    {
        if ($this->status === self::STATUS_DELIVERY) {
            return false; // Cannot change from delivered
        }

        return in_array($newStatus, self::STATUS_TRANSITIONS[$this->status] ?? []);
    }

    public function transitionTo(string $newStatus, ?int $changedBy = null, ?string $notes = null, ?array $metadata = null): bool
    {
        if (!$this->canTransitionTo($newStatus)) {
            return false;
        }

        $oldStatus = $this->status;
        $this->status = $newStatus;

        // Set dispatch/delivery dates
        if ($newStatus === self::STATUS_DISPATCH && !$this->dispatch_date) {
            $this->dispatch_date = now();
        } elseif ($newStatus === self::STATUS_DELIVERY && !$this->delivery_date) {
            $this->delivery_date = now();
        }

        // Increment version for optimistic locking
        $this->version = $this->version + 1;

        // Save the order first
        if (!$this->save()) {
            return false;
        }

        // Log the status change to history
        try {
            OrderStatusHistory::logStatusChange(
                $this,
                $oldStatus,
                $newStatus,
                $changedBy,
                $notes,
                $metadata
            );
        } catch (\Exception $e) {
            // Log the error but don't fail the status update
            \Log::error("Failed to log status change for order {$this->order_number}: " . $e->getMessage());
        }

        return true;
    }

    public function getNextStatusOptions(): array
    {
        return self::STATUS_TRANSITIONS[$this->status] ?? [];
    }

    public function getStatusProgressAttribute(): int
    {
        return match($this->status) {
            self::STATUS_PENDING => 10,
            self::STATUS_PROCESSING => 30,
            self::STATUS_DISPATCH => 75,
            self::STATUS_DELIVERY => 100,
            default => 0,
        };
    }

    public function getStatusProgressClassAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'progress-affix',
            self::STATUS_DISPATCH => 'progress-info',
            self::STATUS_DELIVERY => 'progress-success',
            default => 'progress-secondary',
        };
    }

    // Get status color for UI (backward compatibility)
    public function getStatusColor()
    {
        return self::STATUS_COLORS[$this->status] ?? 'secondary';
    }

    // Get status label
    public function getStatusLabel()
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);
    }

    // Status label accessor
    public function getStatusLabelAttribute()
    {
        return $this->getStatusLabel();
    }

    // Next status options accessor
    public function getNextStatusOptionsAttribute()
    {
        return $this->getNextStatusOptions();
    }

    // Check if order can be modified (not delivered)
    public function canModify()
    {
        return $this->status !== self::STATUS_DELIVERY;
    }

    // Check if order can be deleted
    public function canDelete(): bool
    {
        return $this->status === self::STATUS_PENDING && !$this->isProcessing();
    }

    // Check if order is in processing (dispatched but not delivered)
    public function isProcessing(): bool
    {
        return $this->status === self::STATUS_DISPATCH;
    }

    // Check if order is completed (delivered)
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_DELIVERY;
    }

    // Get processing time in days
    public function getProcessingDaysAttribute(): int
    {
        if (!$this->dispatch_date || !$this->delivery_date) {
            return 0;
        }

        return $this->dispatch_date->diffInDays($this->delivery_date);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeDispatched($query)
    {
        return $query->where('status', self::STATUS_DISPATCH);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_DELIVERY);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }
}
