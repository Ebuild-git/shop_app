<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class posts extends Model
{
    use HasFactory;
    protected $fillable = [
        'sell_at',
        'id_user_buy'
    ];





    public function sous_categorie_info()
    {
        return $this->hasOne(sous_categories::class, 'id', 'id_sous_categorie');
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

    public function acheteur(){
        return $this->belongsTo(User::class,"id_user_buy","id");
    }

    public function getLike(){
        return $this->hasMany(likes::class,'id_post','id');
    }
}
