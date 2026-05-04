<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class, 'city_id');
    }

    public function region()
    {
        // If cities table later has region_id, you can define:
        // return $this->belongsTo(Region::class);
        return $this->belongsTo(regions::class, 'region_id'); // optional future
    }
}
