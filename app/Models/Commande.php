<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_vendor',
        'id_buyer',
        'id_post',
        'frais_livraison',
        'etat',
        'statut',
        'shipment_id'
    ];


    public function vendor()
    {
        return $this->belongsTo(User::class, 'id_vendor');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'id_buyer');
    }

    public function post()
    {
        return $this->belongsTo(posts::class, 'id_post');
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }

}
