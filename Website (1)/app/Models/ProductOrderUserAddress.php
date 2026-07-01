<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrderUserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'guest_user_id',
        'order_id',
        'address_line_one',
        'address_line_two',
        'landmark',
        'area_id',
        'city',
        'state',
        'pincode',
        'address_phone_number',
        'address_type_id',
        'address_type_name',
        'address_type_others_name',
    ];

    protected $casts = [
        'area_id' => 'integer',
        'pincode' => 'integer',
        'address_type_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_number');
    }
}
