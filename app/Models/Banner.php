<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'link', 'path', 'is_active'];

    public function scopeActive($query)
    {
        $query->where('is_active', 1);
    }
}
