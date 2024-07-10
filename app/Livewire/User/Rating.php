<?php

namespace App\Livewire\User;

use App\Models\posts;
use App\Models\ratings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Rating extends Component
{
    public $user, $notes, $ma_note = 0;

    public function mount($user)
    {
        $this->user = $user;
    }

    public function render()
    {
        $this->notes = ratings::where('id_user_sell', $this->user->id)->avg('etoiles');
        $ma_note = ratings::where('id_user_buy', Auth::user()->id)
            ->where("id_user_sell", $this->user->id)
            ->first();
        if ($ma_note) {
            $this->ma_note = $ma_note->etoiles;
        }
        $count = number_format($this->user->averageRating->average_rating ?? 1);
        $avis = $this->user->getReviewsAttribute->count();
        return view('livewire.user.rating', compact("count", "avis"));
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

        $rate = ratings::where('id_user_buy', Auth::user()->id)
            ->where("id_user_sell", $this->user->id)
            ->Orderby("created_at","Asc")
            ->first();

        if(!$rate){
            $this->dispatch('alert2', ['message' => "Vous pouvez laisser un avis sous forme d'étoiles uniquement si vous avez effectué un achat auprès de ce vendeur !", 'type' => 'warning','time'=>5000]);
            return;
        }

        // veridier que il n'a pas encore donner une note
        if ($rate->etoiles != null) {
            $this->dispatch('alert', ['message' => "Vous ne pouvez pas modifier votre avis!", 'type' => 'warning']);
            return;
        } 

        //on verifie que sa fais moins de 2 semaine qu'il a fait cet achat
        $date = Carbon::now();
        $date = $date->subDays(14);
        if ($rate->created_at < $date) {
            $this->dispatch('alert', ['message' => "Vous ne pouvez pas donner une note plus de 2 semaine après votre achat!", 'type' => 'warning']);
            return;
        }

        //tout est ok on enregistre la note
        $rate->etoiles = $value;
        $rate->save();
        $this->dispatch('alert', ['message' => "Votre note a été enregistré !", 'type' => 'success']);
        
    }
}
