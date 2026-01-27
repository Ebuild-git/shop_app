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
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

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
        'photo_verified_at',
        'last_login_at',
        'locale',
        'email_deleted', 'region', 'address', 'rue',
        'nom_batiment', 'etage', 'num_appartement', 'phone_number'
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

    protected $appends = [
        'total_sales_count',
        'total_categories_count'
    ];


    public function getTotalSalesCountAttribute()
    {
        return $this->total_sales()->count();
    }

    public function getTotalCategoriesCountAttribute()
    {
        return $this->categoriesWhereUserSell();
    }

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

    public function ValidatedPosts()
    {
        return $this->hasMany(Posts::class, 'id_user', 'id')
                    ->where('statut', '!=', 'validation');
    }


    public function region()
    {
        return $this->belongsTo(regions::class, 'id', 'region');
    }

    public function region_info()
    {
        return $this->belongsTo(regions::class,'region', 'id');
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






    public function categoriesWhereUserSell()
    {
        $posts = posts::where('id_user', $this->id)
                      ->whereIn('statut', ['livré', 'vendu', 'préparation'])
                      ->get();
        $categories = [];
        $total = 0;

        foreach ($posts as $post) {
            $sous_categorie = sous_categories::find($post->id_sous_categorie);
            if ($sous_categorie) {
                $categorie = categories::find($sous_categorie->id_categorie);
                if ($categorie && !isset($categories[$categorie->titre])) {
                    $categories[$categorie->titre] = true;
                    $total += 1;
                }
            }
        }

        return $total;
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

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function isIdentityVerified()
    {
        if ($this->email_verified_at) {
            return true;
        }
        return false;
    }

    public function violations()
    {
        return $this->hasMany(signalements::class, 'id_user_make');
    }

    public function getViolationsHistory($userId)
    {
        return signalements::where('id_user_make', $userId)
            ->with('post') // Assuming you want to include related post data
            ->get();
    }

    public function signalementsOnMyPosts()
    {
        return signalements::whereIn('id_post', function($query) {
            $query->select('id')
                ->from('posts')
                ->where('id_user', $this->id);
        })->orderBy('created_at', 'desc')->get();
    }


    public function getRibNumberAttribute($value)
    {
        try {
            return $value ? Crypt::decryptString($value) : null;
        } catch (\Exception $e) {
            return $value; // already plain text
        }
    }

    public function setRibNumberAttribute($value)
    {
        $this->attributes['rib_number'] = $value
            ? Crypt::encryptString($value)
            : null;
    }

}
