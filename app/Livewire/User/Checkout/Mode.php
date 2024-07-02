<?php

namespace App\Livewire\User\Checkout;

use App\Models\posts;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Mode extends Component
{
    public $user;


    public function mount(){
        $this->user = Auth::user();
    }

    public function render()
    {
        $articles_panier = [];
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
                $articles_panier[] = [
                    "id" => $post->id,
                    "titre" => $post->titre,
                    "prix" => $post->getPrix(),
                    "photo" => $post->photos[0],
                    "vendeur" => $post->user_info->username,
                    "is_solder" => $post->old_prix ? true : false,
                    "old_prix" => $post->old_prix
                ];
                $total += round($post->getPrix(), 3);
                $nbre_article++;
            }
        }
        
        return view('livewire.user.checkout.mode')
        ->with("articles_panier", $articles_panier)
        ->with("total", $total)
        ->with("nbre_article", $nbre_article);
    }
}
