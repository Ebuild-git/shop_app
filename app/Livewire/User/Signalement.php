<?php

namespace App\Livewire\User;

use App\Models\signalements;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Signalement extends Component
{

    public $post, $type, $message;
    public function mount($post)
    {
        $this->post = $post;
    }

    protected $rules = [
        'type' => 'required|string',
        'message' => 'required|min:15'
    ];

    public function render()
    {
        return view('livewire.user.signalement');
    }

    public function signaler()
    {
        $this->validate();

        //verifier si cet utilisateur a deja un signalement pour  ce post
        if (signalements::where('id_user_make', Auth::use()->id)
            ->where('id_post', $this->post->id)->exists()
        ) {
            session()->flash('error', 'Vous avez déjà signalé cette publication');
            return;
        }


        $signalement = new signalements();
        $signalement->id_post = $this->post->id;
        $signalement->id_user_make = Auth::user()->id;
        $signalement->type = $this->type;
        $signalement->message = $this->message;
        $signalement->save();
        session()->flash("success", "Votre signalement a été enregistré");

        //reset form
        $this->reset(['type','message']);
    }
}
