<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'region', 'city', 'street', 'building_name', 'floor', 'apartment_number', 'phone_number', 'is_default'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function regionExtra()
    {
        return $this->belongsTo(regions::class, 'region', 'id');
    }
}
