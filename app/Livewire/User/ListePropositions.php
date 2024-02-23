<?php

namespace App\Livewire\User;

use App\Models\propositions;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ListePropositions extends Component
{
    public $post,$propositions;

    public function mount($post){
        $this->post = $post;
    }
    
    public function render()
    {
        $this->propositions = $this->post->propositions;
        
        return view('livewire.user.liste-propositions');
    }


    public function supprimer($id_acheteur){
        $proposition = propositions::where("id_acheteur",$id_acheteur)
        ->where("id_post",$this->post->id)
        ->where("id_vendeur",Auth::id())
        ->first();
        if($proposition){
            $proposition->etat = "refusé";
            $proposition->save();
        }

    }

    public function retaurer(){
        $proposition = propositions::where("id_post",$this->post->id)
        ->where("id_vendeur",Auth::id())->get();
        if($proposition){
            foreach ($proposition as $p) {
                $p->etat="traitement";
                $p->save();
            }
        }
    }
}
