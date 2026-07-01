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
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'item_options' => 'array',
    ];

    /**
     * Polymorphic relationship to item (Product or ProductVariant)
     */
    public function itemable()
    {
        return $this->morphTo();
    }

    /**
     * Relationship back to order
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Helper method to calculate line total
     */
    public function getLineTotal()
    {
        return (float)$this->unit_price * (int)$this->quantity;
    }
}
