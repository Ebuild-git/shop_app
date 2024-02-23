<?php

namespace App\Livewire\User;

use App\Models\propositions;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MakeProposition extends Component
{

    public $post;
    public function mount($post)
    {
        $this->post = $post;
    }

    public function render()
    {
        return view('livewire.user.make-proposition');
    }

    public function proposer()
    {

        //verifier si cet utilisateur n'a jamais fais de proposition pour ce pots
        $proposition = propositions::where('id_acheteur', Auth::id())->where('id_post', $this->post->id)->first();
        if ($proposition) {
            session()->flash("error", "Vous avez déjà proposé une commande pour cet article !");
            return;
        }
        $proposition = new propositions();
        $proposition->id_post = $this->post->id;
        $proposition->id_acheteur = Auth::user()->id;
        $proposition->id_vendeur = $this->post->id_user;
        $proposition->save();

        //afficher le message de filicitation
        session()->flash("success", "Votre proposition a bien été envoyée au vendeur!");
    }
}
