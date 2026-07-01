<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'address_username',
        'address_first_name',
        'address_last_name',
        'user_id',
        'guest_user_id',
        'address_line_one',
        'address_line_two',
        'landmark',
        'area_id',
        'area_name',
        'city',
        'city_id',
        'state_id',
        'pincode',
        'pincode_id',
        'district',
        'state',
        'address_phone_number',
        'address_type_id',
        'address_type_name',
        'address_type_others_name',
    ];

    protected $casts = [
        'area_id' => 'integer',
        'city_id' => 'integer',
        'state_id' => 'integer',
        'pincode' => 'integer',
        'pincode_id' => 'integer',
        'address_phone_number' => 'string',
        'address_type_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
