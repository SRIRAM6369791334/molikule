<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRecord extends Model
{
    protected $fillable = [
        'product_name',
        'sku',
        'category_name',
        'brand_name',
        'product_full_data',
        'category_full_data',
        'brand_full_data',
        'variants_full_data',
    ];

    protected $casts = [
        'product_full_data' => 'json',
        'category_full_data' => 'json',
        'brand_full_data'    => 'json',
        'variants_full_data' => 'json',
    ];
}
