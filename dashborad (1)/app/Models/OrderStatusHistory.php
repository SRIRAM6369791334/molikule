<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'order_status_histories';

    protected $fillable = [
        'order_id',
        'old_status',
        'new_status',
        'changed_by',
        'notes',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    // Scopes
    public function scopeForOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('changed_by', $userId);
    }

    public function scopeStatusChange($query, $fromStatus, $toStatus)
    {
        return $query->where('old_status', $fromStatus)->where('new_status', $toStatus);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getStatusChangeDescriptionAttribute()
    {
        $oldLabel = Order::STATUS_LABELS[$this->old_status] ?? ucfirst($this->old_status);
        $newLabel = Order::STATUS_LABELS[$this->new_status] ?? ucfirst($this->new_status);

        return "Changed from {$oldLabel} to {$newLabel}";
    }

    public function getChangedAtFormattedAttribute()
    {
        return $this->created_at->format('M d, Y H:i');
    }

    // Static methods for logging
    public static function logStatusChange(Order $order, string $oldStatus, string $newStatus, ?int $changedBy = null, ?string $notes = null, ?array $metadata = null)
    {
        return static::create([
            'order_id' => $order->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => $changedBy,
            'notes' => $notes,
            'metadata' => $metadata
        ]);
    }
}
