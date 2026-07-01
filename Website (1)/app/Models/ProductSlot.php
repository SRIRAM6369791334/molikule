<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_date',
        'order_id',
        'product_id',
        'product_varient_id',
        'product_name',
        'order_name',
        'product_image',
        'product_rate',
        'gst_amt',
        'gst_per',
        'product_value',
        'quantity',
        'product_total',
        'shipping',
        'discount',
        'size_value',
        'color_value',
        'delivery_status',
        'preorder',
        'dispatch_date',
        'order_delivered_time',
        'deliver_person_id',
        'is_cancelled',
        'cancel_reason',
        'approve_staus',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'product_varient_id' => 'integer',
        'quantity' => 'integer',
        'delivery_status' => 'integer',
        'preorder' => 'integer',
        'is_cancelled' => 'integer',
        'approve_staus' => 'integer',
        'dispatch_date' => 'date',
        'order_delivered_time' => 'datetime',
        'product_rate' => 'decimal:2',
        'product_total' => 'decimal:2',
        'shipping' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_number');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_varient_id');
    }
}
