<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class propositions extends Model
{
    use HasFactory;
     protected $fillable = [
        'etat',
     ];


    public  function acheteur()
    {
        return $this->hasOne(User::class, 'id', 'id_acheteur');
    }

    public function post()
    {
        return   $this->belongsTo(posts::class, 'id', 'id_post');
    }
}
