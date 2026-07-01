<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Analytics extends Model
{
    use HasFactory;

    protected $table = 'analytics';

    protected $fillable = [
        'event_type',
        'entity_type',
        'entity_id',
        'user_id',
        'ip_address',
        'user_agent',
        'metadata',
        'recorded_at'
    ];

    protected $casts = [
        'metadata' => 'json',
        'recorded_at' => 'datetime'
    ];

    public $timestamps = false; // Using recorded_at instead

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->recorded_at = $model->recorded_at ?? now();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
