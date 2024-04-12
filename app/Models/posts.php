<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class posts extends Model
{
    use HasFactory;
    protected $fillable = [
        'sell_at',
        'id_user_buy',
        'prix'
    ];

    protected $casts = [
        'photos' => 'json',
        'proprietes' => 'json'
    ];


    public function getPrix()
    {
        $pourcentage_gain = $this->sous_categorie_info->categorie->pourcentage_gain;
        $prix = round($this->attributes['prix'] + (($pourcentage_gain * $this->attributes['prix']) / 100), 3);
        return $prix ?? "N/A";
    }

    
    public function getOldPrix(){

        $pourcentage_gain = $this->sous_categorie_info->categorie->pourcentage_gain;
        $prix = round($this->attributes['old_prix'] + (($pourcentage_gain * $this->attributes['old_prix']) / 100), 3);
        return $prix ?? "N/A";
    }

    public function getFraisLivraison()
    {
        $id_categorie = $this->sous_categorie_info->categorie->id;
        $id_region = $this->region->id;

        $val = regions_categories::where('id_region', $id_region)->where('id_categorie', $id_categorie)->first();
        if ($val) {
            return $val->prix;
        } else {
            return  'N/A';
        }
    }


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
    public  function propositions()
    {
        return $this->hasMany(propositions::class, 'id_post', 'id');
    }

    public function acheteur()
    {
        return $this->belongsTo(User::class, "id_user_buy", "id");
    }

    public function getLike()
    {
        return $this->hasMany(likes::class, 'id_post', 'id');
    }

    public function region()
    {
        return $this->belongsTo(regions::class, 'id_region', 'id');
    }


    public function signalements(){
        return $this->hasMany(signalements::class, 'id_post', 'id');
    }


}
