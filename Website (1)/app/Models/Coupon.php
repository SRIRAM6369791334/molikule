<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Coupon extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'min_cart_value',
        'starts_at',
        'expires_at',
        'usage_limit',
        'user_limit',
        'status'
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_cart_value' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'status' => 'boolean'
    ];

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Validate a coupon.
     *
     * @param  float       $cartTotal
     * @param  int|null    $userId
     * @param  string|null $email
     * @param  int|null    $lockedUsageCount  Pass the usage count read inside a FOR UPDATE lock.
     *                                         When null, a fresh live count is queried (for pre-flight checks only).
     * @return array [bool $valid, string|null $error]
     */
    public function isValid($cartTotal, $userId = null, $email = null, $lockedUsageCount = null)
    {
        if (!$this->status)
            return [false, 'Coupon is inactive.'];
        if ($this->starts_at && $this->starts_at->isFuture())
            return [false, 'Coupon not yet active.'];
        if ($this->expires_at && $this->expires_at->isPast())
            return [false, 'Coupon expired.'];
        if ($cartTotal < (float) $this->min_cart_value)
            return [false, "Minimum cart value of ₹{$this->min_cart_value} required."];

        // ---------------------------------------------------------------
        // CONCURRENCY GUARD: Use the caller-supplied count when available.
        // When called from CheckoutController (inside a FOR UPDATE lock),
        // $lockedUsageCount is the count read AFTER the row was locked,
        // ensuring no other transaction can slip through simultaneously.
        // ---------------------------------------------------------------
        $totalUsed = $lockedUsageCount ?? $this->usages()->count();

        if ($this->usage_limit && $totalUsed >= (int) $this->usage_limit) {
            return [false, 'Coupon limit reached.'];
        }

        // Per-user limit (always live-queried; user-specific, low contention)
        if ($this->user_limit) {
            $count = 0;
            if ($userId) {
                $count = $this->usages()->where('user_id', $userId)->count();
            } elseif ($email) {
                $count = $this->usages()->where('customer_email', $email)->count();
            }

            if ($count >= (int) $this->user_limit) {
                return [false, 'You have reached the usage limit for this coupon.'];
            }
        }

        return [true, null];
    }

    public function calculateDiscount($total)
    {
        $discount = $this->discount_type === 'percentage'
            ? (float) $total * ((float) $this->discount_value / 100)
            : (float) $this->discount_value;

        return min((float) $total, round($discount, 2));
    }
}
