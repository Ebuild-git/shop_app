<?php

namespace App\Livewire\User\Checkout;

use App\Mail\commande;
use App\Models\posts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Mode extends Component
{
    public $user,$articles_panier;


    public function mount(){
        $this->user = Auth::user();
    }

    public function render()
    {
        $total = 0;
        $nbre_article = 0;

        $cart = json_decode($_COOKIE['cart'] ?? '[]', true);

        foreach ($cart as $item) {
            $post = posts::join('sous_categories', 'posts.id_sous_categorie', '=', 'sous_categories.id')
                ->join('categories', 'sous_categories.id_categorie', '=', 'categories.id')
                ->select("categories.pourcentage_gain", "posts.prix", "posts.id_user", "posts.id_sous_categorie", "posts.id",  "posts.titre", "posts.photos", "posts.old_prix")
                ->where("posts.id", $item)
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


        //send mail
        dd($this->articles_panier);
        Mail::to("kevingassam23@gmail.com")->send(new commande($this->user,$this->articles_panier));
    }

}
