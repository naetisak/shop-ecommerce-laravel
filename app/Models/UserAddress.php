<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'is_default_address',
        'tag',
        'first_name',
        'last_name',
        'mobile_no',
        'street_address',
        'district',
        'state',
        'pin_code',
        'note',
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getFullAddressAttribute()
    {
        return "$this->street_address, $this->district, $this->state - $this->pin_code";
    }

}

