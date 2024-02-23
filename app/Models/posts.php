<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class posts extends Model
{
    use HasFactory;



    public function categorie_info()
    {
        return $this->hasOne(categories::class, 'id', 'id_categorie');
    }

    //recuperer les informations su l'uilisateur
    public function user_info()
    {
        return $this->hasOne(User::class, 'id', "id_user");
    }

    // recuperation les propositions
    public  function propositions(){
        return $this->hasMany(propositions::class,'id_post','id');
    }
}
