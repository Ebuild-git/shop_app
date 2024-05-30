<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'locked',
        'email',
        'password',
        'first_login_at',
        'photo_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'role'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function GetPosts()
    {
        return $this->hasMany(posts::class, 'id_user', 'id');
    }





    public function region()
    {
        return $this->belongsTo(regions::class, 'id', 'region');
    }

    public function total_sales(){
        return $this->hasMany(posts::class, 'id_user', 'id')->whereNotNull("sell_at");
    }





    public function getAvatar()
    {
        if (is_null($this->avatar)) {
            return "https://t3.ftcdn.net/jpg/05/00/54/28/360_F_500542898_LpYSy4RGAi95aDim3TLtSgCNUxNlOlcM.jpg";
        } else {
                $url =  Storage::url($this->avatar);
                if (Storage::disk('public')->exists($url)) {
                    return $url;
                } else {
                    return "https://t3.ftcdn.net/jpg/05/00/54/28/360_F_500542898_LpYSy4RGAi95aDim3TLtSgCNUxNlOlcM.jpg";
                }
                
        }
    }
    

  


    public function categoriesWhereUserPosted()
    {
        return $this->hasManyThrough(
            categories::class,
            posts::class,
            'id_user', // clé étrangère dans la table posts
            'id',       // clé primaire dans la table categories
            'id',       // clé primaire dans la table users
            'id_sous_categorie' // clé étrangère dans la table posts vers sous_categories
        )->distinct();
    }


    public function categoriesWhereUserSell()
    {
        return $this->hasManyThrough(
            categories::class,
            posts::class,
            'id_user', // clé étrangère dans la table posts
            'id',      // clé primaire dans la table categories
            'id',      // clé primaire dans la table users
            'id_sous_categorie' // clé étrangère dans la table posts vers sous_categories
        )->whereNotNull('posts.id_user_buy')->distinct();
    }

    public function averageRating()
    {
        return $this->hasOne(ratings::class, 'id_user_sell')
            ->selectRaw('AVG(etoiles) as average_rating')
            ->groupBy('id_user_sell');
    }


    public function markFirstLogin()
    {
        if (is_null($this->first_login_at)) {
            $this->update(['first_login_at' => now()]);
        }
    }



    public function pings(){
        return $this->hasMany(pings::class, 'id_user', 'id');
    }

    

    //recuperer les avis de l'utilisateur
    public function getReviewsAttribute(){
        return $this->hasMany(ratings::class, 'id_user_sell','id');
    }

    public function likes(){
        return $this->hasMany(likes::class, 'id_user', 'id');
    }

    public function favoris(){
        return $this->hasMany(favoris::class, 'id_user', 'id');
    }

}
