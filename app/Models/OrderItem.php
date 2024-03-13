<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'variant_id',
        'qty',
        'mrp',
        'price'
    ];

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
