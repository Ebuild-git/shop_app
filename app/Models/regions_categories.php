<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class regions_categories extends Model
{
    use HasFactory;


    public function categorie(){
        return $this->belongsTo(Categories::class,'id_categorie','id');
    }

    public function region(){
        return $this->belongsTo(regions::class, 'id_region', 'id');
    }
}
