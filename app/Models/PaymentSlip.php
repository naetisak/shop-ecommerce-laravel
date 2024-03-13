<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSlip extends Model
{
    protected $table = 'payment_slips'; // ชื่อตารางในฐานข้อมูล

    protected $fillable = [
        'file_path',
    ];
}
