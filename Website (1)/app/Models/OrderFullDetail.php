<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderFullDetail extends Model
{
    protected $table = 'order_full_details';

    protected $fillable = [
        'order_id',
        'order_number',
        'user_id',
        'user_email',
        'user_name',
        'user_phone',
        'product_id',
        'product_name',
        'product_description',
        'product_specification',
        'product_image',
        'product_slug',
        'category_id',
        'category_name',
        'variant_id',
        'variant_name',
        'variant_image',
        'variant_slug',
        'variant_value',
        'variant_size_value',
        'variant_offer_price',
        'variant_mrp_price',
        'variant_quantity',
        'order_quantity',
        'order_unit_price',
        'order_total_price',
        'order_subtotal',
        'order_gst_amount',
        'order_delivery_charge',
        'order_discount_amount',
        'order_grand_total',
        'order_status',
        'payment_method',
        'payment_status',
        'shipping_address',
        'billing_address',
        'order_created_at',
    ];

    protected $casts = [
        'order_id' => 'integer',
        'user_id' => 'integer',
        'product_id' => 'integer',
        'category_id' => 'integer',
        'variant_id' => 'integer',
        'variant_offer_price' => 'decimal:2',
        'variant_mrp_price' => 'decimal:2',
        'variant_quantity' => 'integer',
        'order_quantity' => 'integer',
        'order_unit_price' => 'decimal:2',
        'order_total_price' => 'decimal:2',
        'order_subtotal' => 'decimal:2',
        'order_gst_amount' => 'decimal:2',
        'order_delivery_charge' => 'decimal:2',
        'order_discount_amount' => 'decimal:2',
        'order_grand_total' => 'decimal:2',
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'order_created_at' => 'datetime',
    ];
}
