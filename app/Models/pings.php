<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pings extends Model
{
    use HasFactory;
    protected $fillable = [
        'pined',
        'id_user'
    ];

}
