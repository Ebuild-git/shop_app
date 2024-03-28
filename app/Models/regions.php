<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class regions extends Model
{
    use HasFactory;


    public function getPost(){
        return $this->hasMany(posts::class, 'id_region', 'id');
    }
}
