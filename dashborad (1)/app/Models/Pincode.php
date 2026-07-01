<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pincode extends Model
{
    use HasFactory;

    protected $fillable = [
        'pincode',
        'city',
        'state',
        'country',
        'cod_charge',
        'is_active',
        'priority'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'cod_charge' => 'decimal:2',
        'priority' => 'integer',
    ];

    // Get formatted pincode with city/state
    public function getFormattedLocationAttribute()
    {
        return "{$this->pincode} - {$this->city}, {$this->state}";
    }

    // Status badge accessor
    public function getStatusBadgeAttribute()
    {
        return $this->is_active ?
            '<span class="badge bg-success-subtle text-success"><i class="bx bx-check-circle me-1"></i>Active</span>' :
            '<span class="badge bg-secondary-subtle text-secondary"><i class="bx bx-x-circle me-1"></i>Inactive</span>';
    }

    // Check if pincode is available for delivery
    public function isAvailable()
    {
        return $this->is_active;
    }

    // Scope for active pincodes only
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for searching by pincode, city, or state
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('pincode', 'like', "%{$search}%")
              ->orWhere('city', 'like', "%{$search}%")
              ->orWhere('state', 'like', "%{$search}%");
        });
    }

    // Find pincode by code
    public static function findByPincode($pincode)
    {
        return self::where('pincode', $pincode)->first();
    }

    // Check if pincode is serviceable
    public static function isServiceable($pincode)
    {
        $pincode = self::where('pincode', $pincode)->active()->first();
        return $pincode !== null;
    }

    // Get city/state by pincode
    public static function getLocationByPincode($pincode)
    {
        $pincode = self::where('pincode', $pincode)->active()->first();
        return $pincode ? ['city' => $pincode->city, 'state' => $pincode->state] : null;
    }
}
