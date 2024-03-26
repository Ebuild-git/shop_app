<?php

namespace App\Livewire\User;

use App\Models\ratings;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Rating extends Component
{
    public $user,$notes,$ma_note =0 ;

    public function mount($user)
    {
        $this->user = $user;
    }

    public function render()
    {
        $this->notes = ratings::where('id_user_rated', $this->user->id)->avg('etoiles');
        $ma_note = ratings::where('id_user_rating', Auth::user()->id)
        ->where("id_user_rated", $this->user->id)
        ->first();
        if($ma_note){
            $this->ma_note = $ma_note->etoiles;
        }

        return view('livewire.user.rating');
    }

    public function rate($value)
    {
        //verified $value est between 1 and 5 and it's integer
        if (!isset($value) || ($value < 1 || $value > 5)) {
            session()->flash("error", "Vous devez donner une note entre 1 et 5");
            return;
        }

        // verified ist integer
        $value = intval($value);

        $rate = ratings::where('id_user_rating', Auth::user()->id)
            ->where("id_user_rated", $this->user->id)
            ->first();

        if ($rate) {
            $rate->etoiles = $value;
            $rate->save();
            $this->dispatch('alert', ['message' => "Votre note a été modifié !", 'type' => 'success']);
        } else {
            $rating = new ratings();
            $rating->id_user_rating = Auth::user()->id;
            $rating->id_user_rated = $this->user->id;
            $rating->etoiles = $value;
            $rating->save();
            $this->dispatch('alert', ['message' => "Votre note a été enregistré !", 'type' => 'success']);
        }
    }
}
