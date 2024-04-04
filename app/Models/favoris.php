<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class favoris extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_post',
        'id_user',
        'created_at'
    ];


    public function post(){
        return $this->belongsTo(posts::class, 'id_post');
    }

}
