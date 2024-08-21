<?php

namespace App\Livewire\User\Checkout;

use App\Mail\commande;
use App\Models\posts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\UserCart;
use Livewire\Component;

class Mode extends Component
{
    public $user,$articles_panier;
    public $frais  = 25 ;



    public function mount(){
        $this->user = Auth::user();
    }


    public function render()
    {
        $total = 0;
        $nbre_article = 0;
        $this->articles_panier = [];
        $user_id = auth()->id();

        // Get cart items from UserCart based on user_id
        $cartItems = UserCart::where('user_id', $user_id)->pluck('post_id');

        foreach ($cartItems as $item_id) {
            $post = posts::join('sous_categories', 'posts.id_sous_categorie', '=', 'sous_categories.id')
                ->join('categories', 'sous_categories.id_categorie', '=', 'categories.id')
                ->select("categories.pourcentage_gain", "posts.prix", "posts.id_user", "posts.id_sous_categorie", "posts.id",  "posts.titre", "posts.photos", "posts.old_prix")
                ->where("posts.id", $item_id)
                ->first();

            if ($post) {
                $this->articles_panier[] = [
                    "id" => $post->id,
                    "titre" => $post->titre,
                    "prix" => $post->getPrix(),
                    "photo" => config('app.url').Storage::url($post->photos[0]),
                    "vendeur" => $post->user_info->username,
                    "is_solder" => $post->old_prix ? true : false,
                    "old_prix" => $post->old_prix
                ];
                $total += round($post->getPrix(), 3);
                $nbre_article++;
            }
        }

        return view('livewire.user.checkout.mode')
            ->with("total", $total)
            ->with("nbre_article", $nbre_article);
    }


    public function confirm(){

        //validation des commandes de chaque produit
        foreach($this->articles_panier as $article){
          $post= posts::find($article['id']);
          if($post){
            $post->update(
                [
                    'statut' => 'vendu',
                    'sell_at' => Now(),
                    'id_user_buy' => $this->user->id
                ]
            );
          }
        }


        //send mail
        Mail::to(Auth::user()->email)->send(new commande($this->user,$this->articles_panier));

        //generate random token
        $token = md5(uniqid(rand(), true));

        //delete cart cookies
        Cookie::queue(Cookie::forget('cart'));



        return Redirect("/checkout?step=4&action=$token");
    }
}
