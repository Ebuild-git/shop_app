<?php

namespace App\Livewire\User;

use App\Models\posts;
use Livewire\Component;

class Checkout extends Component
{
    public $cart,$articles=[];
    public function render()
    {
        $this->get();
        return view('livewire.user.checkout');
    }


    public function get(){
        $cart = json_decode($_COOKIE['cart'] ?? '[]', true);
        foreach ( $cart as $item){
            $article = posts::join('sous_categories', 'posts.id_sous_categorie', '=', 'sous_categories.id')
            ->join('categories', 'sous_categories.id_categorie', '=', 'categories.id')
            ->select("categories.pourcentage_gain","posts.prix","posts.titre","posts.photos")
            ->where("posts.id",$item)
            ->first();
            if($article){
                $this->articles=[
                    "article" => $article->titre,
                    "prix" => round($article->prix + (($article->pourcentage_gain*$article->prix) / 100), 3),
                    "photo" => $article->photos,
                ];
            }
        }
    }
}
