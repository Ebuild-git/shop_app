<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class posts extends Model
{
    use HasFactory;
    use SoftDeletes;




    protected $fillable = [
        'sell_at',
        'id_user_buy',
        'prix',
        'id_motif',
        'motif_suppression'
    ];

    protected $casts = [
        'photos' => 'json',
        'proprietes' => 'json'
    ];


    public function getPrix()
    {
        $pourcentage_gain = $this->sous_categorie_info->categorie->pourcentage_gain;
        $prix = round($this->attributes['prix'] + (($pourcentage_gain * $this->attributes['prix']) / 100), 2);
        return $prix ?? "N/A";
    }


    public function getOldPrix()
{
    if ($this->changements_prix->isNotEmpty()) {
        $lastChangementPrix = $this->changements_prix->last();
        $old_prix = $lastChangementPrix ? $lastChangementPrix->old_price : null;
        
        if ($old_prix !== null) {
            $pourcentage_gain = $this->sous_categorie_info->categorie->pourcentage_gain;
            $prix = round($old_prix + (($pourcentage_gain * $old_prix) / 100), 2);
            return $prix;
        }
    }

    return null;
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


    public function signalements()
    {
        return $this->hasMany(signalements::class, 'id_post', 'id');
    }

    public function FirstImage()
    {
        if (!empty($this->photos) && isset($this->photos[0])) {
            $url = Storage::url($this->photos[0]);
        } else {
            $url ="https://t4.ftcdn.net/jpg/04/70/29/97/360_F_470299797_UD0eoVMMSUbHCcNJCdv2t8B2g1GVqYgs.jpg";
        }
        return $url;
    }

    public function next_time_to_edit_price()
    {
        if ($this->updated_price_at) {
            $updatedPriceDate = Carbon::parse($this->updated_price_at);
            $expiryDate = $updatedPriceDate->addWeeks(2);
            $now = Carbon::now();

            // Calculer la différence en jours, heures et minutes
            $diffInDays = $now->diffInDays($expiryDate);
            $diffInHours = $now->copy()->addDays($diffInDays)->diffInHours($expiryDate);
            $diffInMinutes = $now->copy()->addDays($diffInDays)->addHours($diffInHours)->diffInMinutes($expiryDate);

            // Formater le temps restant
            $remainingTime = sprintf('%dj %02dh %02dm', $diffInDays, $diffInHours, $diffInMinutes);
        } else {
            $remainingTime = '-';
        }
        return $remainingTime;
    }


    public function motif()
    {
        return $this->belongsTo(motifs::class, 'id_motif', 'id');
    }

    public function motifs()
    {
        //many
        return $this->hasMany(motifs::class, 'id', 'id_motif');
    }


    public function changements_prix()
    {
        //relation
        return $this->hasMany(History_change_price::class, 'id_post','id');
    }
}
