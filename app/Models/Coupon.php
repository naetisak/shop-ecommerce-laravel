<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_cart_amount',
        'from_valid',
        'till_valid'
    ];

    protected $casts = [
        'from_valid' => 'datetime',
        'till_valid' => 'datetime',
    ];
}
