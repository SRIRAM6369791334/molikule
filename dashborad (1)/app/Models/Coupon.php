<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Coupon extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code', 'discount_type', 'discount_value', 
        'min_cart_value', 'starts_at', 'expires_at', 
        'usage_limit', 'user_limit', 'status'
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_cart_value' => 'decimal:2',
        'starts_at'      => 'datetime',
        'expires_at'     => 'datetime',
        'status'         => 'boolean'
    ];

    public static function boot()
    {
        parent::boot();
        
        // Invalidate cache on change
        static::saved(fn($coupon) => Cache::forget("coupon_{$coupon->code}"));
        static::deleted(fn($coupon) => Cache::forget("coupon_{$coupon->code}"));
    }

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function isValid($cartTotal, $userId = null, $email = null)
    {
        $now = now();

        if (!$this->status) return [false, 'Coupon is inactive.'];
        if ($this->starts_at && $this->starts_at->isFuture()) return [false, 'Coupon not yet active.'];
        if ($this->expires_at && $this->expires_at->isPast()) return [false, 'Coupon expired.'];
        if ($cartTotal < $this->min_cart_value) return [false, "Minimum cart value of ₹{$this->min_cart_value} required."];

        // Overall usage limit
        if ($this->usage_limit && $this->usages()->count() >= $this->usage_limit) {
            return [false, 'Coupon limit reached.'];
        }

        // Per user limit
        if ($this->user_limit) {
            $count = 0;
            if ($userId) {
                $count = $this->usages()->where('user_id', $userId)->count();
            } elseif ($email) {
                $count = $this->usages()->where('customer_email', $email)->count();
            }
            
            if ($count >= $this->user_limit) {
                return [false, 'You have reached the usage limit for this coupon.'];
            }
        }

        return [true, null];
    }

    public function calculateDiscount($total)
    {
        $discount = $this->discount_type === 'percentage'
            ? $total * ($this->discount_value / 100)
            : $this->discount_value;

        return min($total, round($discount, 2));
    }
}
