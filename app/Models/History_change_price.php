<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History_change_price extends Model
{
    use HasFactory;

    protected $fillable = [
        "id_post", "old_price", "new_price"
    ];
}
