<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sous_categories extends Model
{
    use HasFactory;

    //filiable
    protected $fillable = [
        'order'
    ];


    protected $casts = [
        'proprietes' => 'json',
    ];

    

    public function getPost() {
        return $this->hasMany(posts::class, 'id_sous_categorie','id');
    }

    public function categorie()
    {
        return $this->belongsTo(categories::class, 'id_categorie')->orderBy("order","asc");
    }
}
