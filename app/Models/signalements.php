<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class signalements extends Model
{
    use HasFactory;


    public function post(){
        return $this->belongsTo(posts::class, 'id_post');
    }

    public function auteur(){
        return $this->belongsTo(User::class, 'id_user_make');
    }
}
