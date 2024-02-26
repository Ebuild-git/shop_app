<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categories extends Model
{
    use HasFactory;
    protected $fillable = [
        'titre',
        'description',
        'photo'
    ];


    //get list post
    public function getPost() {
        return $this->hasMany(posts::class, 'id_categorie','id');
    }

    //get all sous categorie
    public function getSousCategories(){
        return $this->hasMany(sous_categories::class, 'id_categorie','id');
    }
}
