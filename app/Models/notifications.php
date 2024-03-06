<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notifications extends Model
{
    use HasFactory;


    //get  post
    public function getPost() {
        return $this->hasMany(posts::class, 'id','id_post');
    }


    public function getUser() {
        return $this->hasOne(User::class, 'id','id_user');
    }
}
