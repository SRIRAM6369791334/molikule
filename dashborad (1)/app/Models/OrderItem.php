<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'order_item_id';

    protected $fillable = [
        'order_id',
        'itemable_type',
        'itemable_id',
        'item_name',
        'unit_price',
        'quantity',
        'item_options',
        'total_price'
    ];

    protected $casts = [
        'item_options' => 'array',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * Boot the model and add validation for polymorphic relationships
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $validTypes = [
                'App\Models\Product',
                'App\Models\ProductVariant'
            ];

            if (!in_array($model->itemable_type, $validTypes)) {
                throw new \InvalidArgumentException(
                    "Invalid itemable_type: {$model->itemable_type}. Must be one of: " . implode(', ', $validTypes)
                );
            }

            // Ensure item_name is populated from the related item if not provided
            if (empty($model->item_name) && $model->itemable) {
                $model->item_name = $model->itemable->name ?? $model->itemable->product_name ?? null;
            }
        });

        static::updating(function ($model) {
            $validTypes = [
                'App\Models\Product',
                'App\Models\ProductVariant'
            ];

            if ($model->isDirty('itemable_type') && !in_array($model->itemable_type, $validTypes)) {
                throw new \InvalidArgumentException(
                    "Invalid itemable_type: {$model->itemable_type}. Must be one of: " . implode(', ', $validTypes)
                );
            }
        });

        static::saving(function ($model) {
            // Fallback: if item_name is still empty and we have itemable, populate it
            if (empty($model->item_name) && $model->itemable) {
                $model->item_name = $model->itemable->name ?? $model->itemable->product_name ?? null;
            }
        });
    }

    // Polymorphic relationship to item (Product or ProductVariant)
    public function itemable()
    {
        return $this->morphTo();
    }

    // Relationship back to order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Helper method to get the item (resolves polymorphic)
    public function getItem()
    {
        return $this->itemable;
    }

    // Helper method to calculate line total
    public function getLineTotal()
    {
        return $this->unit_price * $this->quantity;
    }
}
