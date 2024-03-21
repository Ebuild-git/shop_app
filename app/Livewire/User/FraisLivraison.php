<?php

namespace App\Livewire\User;

use App\Models\posts;
use App\Models\regions_categories;
use Livewire\Component;

class FraisLivraison extends Component
{


    public $frais_livraison =0,$id_post;

    public function mount($id_post){
        $this->id_post = $id_post;
    }


    public function render()
    {
        $post = posts::join('sous_categories', 'posts.id_sous_categorie', '=', 'sous_categories.id')
        ->join('categories', 'sous_categories.id_categorie', '=', 'categories.id')
        ->select("categories.id As id_categorie","posts.id_region")
        ->first();
        if($post){
            $frais_livraison = regions_categories::where("id_region",$post->id_region)
            ->where("id_categorie",$post->id_categorie)
            ->select("prix")
            ->first();
            if($frais_livraison){
                $this->frais_livraison = $frais_livraison->prix;
            }
        }

       
        return view('livewire.user.frais-livraison');
    }
}
