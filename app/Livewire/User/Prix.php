<?php

namespace App\Livewire\User;

use App\Models\posts;
use Livewire\Component;

class Prix extends Component
{
    public $prix,$id_post;

    public function mount($id_post){
        $this->id_post = $id_post;
    }

    public function render()
    {
        $post = posts::join('sous_categories', 'posts.id_sous_categorie', '=', 'sous_categories.id')
        ->join('categories', 'sous_categories.id_categorie', '=', 'categories.id')
        ->where('categories.luxury', true)
        ->select("categories.pourcentage_gain","posts.prix")
        ->first();
        $pourcentage_gain = $post->pourcentage_gain;
        //$frais_livraison = $post->frais_livraison;

        $this->prix = round($post->prix + (($pourcentage_gain/$post->prix) * 100), 3);
        return view('livewire.user.prix');
    }
}
