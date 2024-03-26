<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'certifier',
        'first_login_at'
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

    public function GetPosts(){
        return $this->hasMany(posts::class, 'id_user','id');
    }

    public function region(){
        return $this->belongsTo(regions::class,'id','region');
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

    public function averageRating()
    {
        return $this->hasOne(ratings::class, 'id_user_rated')
                    ->selectRaw('AVG(etoiles) as average_rating')
                    ->groupBy('id_user_rated');
    }


    public function markFirstLogin()
    {
        if (is_null($this->first_login_at)) {
            $this->update(['first_login_at' => now()]);
        }
    }
    
}
