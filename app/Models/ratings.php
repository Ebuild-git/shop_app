<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ratings extends Model
{
    use HasFactory;



    public function getPost()
    {
        return $this->belongsTo(posts::class, 'id_region', 'id');
    }
}
