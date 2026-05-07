<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'region', 'city', 'street', 'building_name', 'floor', 'apartment_number', 'phone_number', 'is_default', 'city_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function regionExtra()
    {
        return $this->belongsTo(regions::class, 'region', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
}
