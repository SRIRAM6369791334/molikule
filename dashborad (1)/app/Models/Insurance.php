<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Insurance extends Model
{
    use HasFactory;

    protected $table = 'insurance_quotes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'insurance_type',
        'proposer_name',
        'proposer_email',
        'proposer_mobile',
        'alternate_mobile',
        'landline_number',
        'pan_number',
        'registration_number',
        'vehicle_make',
        'vehicle_model',
        'vehicle_variant',
        'first_registration_date',
        'year_manufacture',
        'engine_cc',
        'chassis_number',
        'engine_number',
        'body_type',
        'vehicle_colour',
        'fuel_type',
        'gross_weight',
        'seating_capacity',
        'insured_value',
        'purpose_use',
        'area_operation',
        'vehicle_condition',
        'is_financed',
        'image_path',
        'vehicle_type',
        'vehicle_value',
        'coverage_type',
        'message',
        'status',
        'quote_number',
        'quote_data',
        'quoted_premium',
        'admin_notes',
        'quoted_at',
    ];

    protected $casts = [
        'first_registration_date' => 'date',
        'quoted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_financed' => 'boolean',
        'insured_value' => 'decimal:2',
        'vehicle_value' => 'decimal:2',
        'quoted_premium' => 'decimal:2',
        'gross_weight' => 'decimal:2',
        'year_manufacture' => 'integer',
        'engine_cc' => 'integer',
        'seating_capacity' => 'integer',
        'quote_data' => 'json',
    ];

    const STATUS_NEW = 'new';
    const STATUS_PENDING = 'pending';
    const STATUS_QUOTED = 'quoted';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';

    const STATUS_COLORS = [
        self::STATUS_NEW => 'secondary',
        self::STATUS_PENDING => 'warning',
        self::STATUS_QUOTED => 'info',
        self::STATUS_ACCEPTED => 'success',
        self::STATUS_REJECTED => 'danger',
    ];

    const STATUS_LABELS = [
        self::STATUS_NEW => 'New',
        self::STATUS_PENDING => 'Pending',
        self::STATUS_QUOTED => 'Quoted',
        self::STATUS_ACCEPTED => 'Accepted',
        self::STATUS_REJECTED => 'Rejected',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeAttribute()
    {
        $color = self::STATUS_COLORS[$this->status] ?? 'secondary';
        $label = self::STATUS_LABELS[$this->status] ?? $this->status;
        return "<span class=\"badge bg-$color\">$label</span>";
    }

    public function getFormattedVehicleAttribute()
    {
        $parts = array_filter([
            $this->vehicle_make,
            $this->vehicle_model,
            $this->vehicle_variant
        ]);
        return implode(' ', $parts) ?: 'N/A';
    }

    public function getProgressAttribute()
    {
        $completedFields = 0;
        $totalFields = 15;

        $fields = [
            'proposer_name', 'proposer_email', 'proposer_mobile',
            'pan_number', 'registration_number',
            'vehicle_make', 'vehicle_model', 'year_manufacture',
            'engine_cc', 'chassis_number', 'body_type',
            'fuel_type', 'insured_value', 'purpose_use', 'vehicle_condition'
        ];

        foreach ($fields as $field) {
            if (!is_null($this->$field) && $this->$field !== '') {
                $completedFields++;
            }
        }

        return (int) ($completedFields / $totalFields * 100);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('customer_name', 'like', "%{$search}%")
              ->orWhere('customer_email', 'like', "%{$search}%")
              ->orWhere('customer_phone', 'like', "%{$search}%")
              ->orWhere('quote_number', 'like', "%{$search}%")
              ->orWhere('registration_number', 'like', "%{$search}%");
        });
    }
}
