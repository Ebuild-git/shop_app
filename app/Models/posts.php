<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class posts extends Model
{
    use HasFactory;
    use SoftDeletes;



    protected $fillable = [
        'sell_at',
        'id_user_buy',
        'prix',
        'id_motif',
        'statut',
        'motif_suppression',
    ];

    protected $casts = [
        'photos' => 'json',
        'proprietes' => 'json'
    ];

    protected $dates = [
        'updated_price_at',
        // d'autres colonnes de date
    ];



    public function getPrix()
    {
        $pourcentage_gain = $this->sous_categorie_info->categorie->pourcentage_gain;
        $prix = $this->attributes['prix'];
        $prix_calculé = round($prix + (($pourcentage_gain * $prix) / 100), 2);

        return number_format($prix_calculé, 2, '.', '') ?? "N/A";
    }



    public function getOldPrix()
    {
        if ($this->changements_prix->isNotEmpty()) {
            $firstChangementPrix = $this->changements_prix->first();
            $old_prix = $firstChangementPrix ? $firstChangementPrix->old_price : null;
            if ($old_prix !== null) {
                $pourcentage_gain = $this->sous_categorie_info->categorie->pourcentage_gain;
                $prix_calculé = round($old_prix + (($pourcentage_gain * $old_prix) / 100), 2);
                return number_format($prix_calculé, 2, '.', '') ?? "N/A";
            }
        }
        return null;
    }




    public function Prix_initial(){
        if($this->changements_prix->count() > 0){
            $prix =  $this->changements_prix->first()->old_price;
            return number_format($prix, 2, '.', '')?? null;
        }else{
            return null;
        }
    }

    public function sous_categorie_info()
    {
        return $this->hasOne(sous_categories::class, 'id', 'id_sous_categorie');
    }

    public function calculateGain()
    {
        $price = $this->old_prix ?: $this->prix;
        return $price;
    }

    public function user_info()
    {
        return $this->hasOne(User::class, 'id', "id_user");
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
            $url = "https://t4.ftcdn.net/jpg/04/70/29/97/360_F_470299797_UD0eoVMMSUbHCcNJCdv2t8B2g1GVqYgs.jpg";
        }
        return $url;
    }

    public function next_time_to_edit_price()
    {
        if ($this->updated_price_at) {
            $updatedPriceDate = Carbon::parse($this->updated_price_at);
            $expiryDate = $updatedPriceDate->addWeeks(1);
            $now = Carbon::now();

            // Vérifier si la date d'expiration est supérieure à la date actuelle
            if ($expiryDate <= $now) {
                return false;
            }

            // Calculer la différence en jours, heures et minutes
            $diffInDays = $now->diffInDays($expiryDate);
            $diffInHours = $now->copy()->addDays($diffInDays)->diffInHours($expiryDate);
            $diffInMinutes = $now->copy()->addDays($diffInDays)->addHours($diffInHours)->diffInMinutes($expiryDate);

            // Formater le temps restant
            return sprintf('%dj %02dh %02dm', $diffInDays, $diffInHours, $diffInMinutes);
        } else {
            return false;
        }
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
        return $this->hasMany(History_change_price::class, 'id_post', 'id');
    }

    public function favoris()
    {
        return $this->hasMany(favoris::class, 'id_post');
    }

    public function getStatusAttribute()
    {
        if ($this->statut === 'vendu' && $this->delivered_at) {
            return 'Terminé'; // Sold and delivered
        }

        if ($this->statut === 'refusé' || !is_null($this->motif_suppression)) {
            return 'Suspendu'; // Suspended or flagged
        }

        if ($this->statut === 'validation' && is_null($this->verified_at)) {
            return 'En attente'; // Waiting for validation
        }

        if ($this->statut === 'validation' || $this->statut === 'vente') {
            if (!is_null($this->verified_at) && is_null($this->sell_at)) {
                return 'Actif'; // Active for sale
            }
        }

        return 'Unknown'; // Default case if no conditions matched
    }

    public function getLastActionDateAttribute()
    {
        $lastSignalement = $this->signalements()->latest()->first(); // Assuming a signalement has a created_at timestamp
        return $lastSignalement ? $lastSignalement->created_at : $this->created_at; // Fall back to post creation date if no signalement exists
    }


}
