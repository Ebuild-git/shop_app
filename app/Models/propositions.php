<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class propositions extends Model
{
    use HasFactory;


    public  function acheteur(){
        return $this->hasOne(User::class,'id','id_acheteur');
    }

}





