<?php

namespace App\Livewire\User;

use App\Models\posts;
use App\Models\ratings;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Rating extends Component
{
    public $id_user, $user, $last_buy, $rate;
    public $can_rate = true;

    public function mount($id_user)
    {
        $this->id_user = $id_user;
        $this->user = User::find($id_user);
        $this->last_buy = posts::where('id_user', $id_user)
            ->where('id_user_buy', Auth::id())
            ->whereIn('statut', ['livré', 'vendu'])
            ->Orderby("sell_at", "Desc")
            ->first();
        $this->rate = ratings::where('id_user_buy', Auth::user()->id)
            ->where("id_user_sell", $this->user->id)
            ->where('id_post', $this->last_buy->id)
            ->Orderby("created_at", "Desc")
            ->first();
    }

    public function render()
    {
        if ($this->rate) {
            $this->can_rate = false;
        }

        if (!$this->last_buy) {
            $this->can_rate = false;
        }
        return view('livewire.user.rating');
    }

    public function rate_action($value)
    {

        $value = intval($value);
        //verified $value est between 1 and 5 and it's integer
        if (!isset($value) || ($value < 1 || $value > 5)) {
            session()->flash("error", "Vous devez donner une note entre 1 et 5");
            return;
        }


        if (!$this->last_buy) {
            session()->flash("error", "Vous ne pouvez laisser un avis sous forme d'étoiles uniquement si vous avez effectué un achat auprès de ce vendeur!");
            return;
        }



        // veridier que il n'a pas encore donner une note
        if ($this->rate) {
            $this->dispatch('alert', ['message' => "Vous ne pouvez pas modifier votre avis!", 'type' => 'warning']);
            return;
        }

        //on verifie que sa fais moins de 2 semaine qu'il a fait cet achat
        $date = Carbon::now();
        $date = $date->subDays(14);
        if ($this->last_buy->sell_at < $date) {
            session()->flash("error", "Vous ne pouvez pas donner une note plus de 2 semaine après votre achat!");
            return;
        }

        //tout est ok on enregistre la note
        $rate = new ratings();
        $rate->id_user_buy = Auth::user()->id;
        $rate->id_user_sell = $this->user->id;
        $rate->id_post = $this->last_buy->id;
        $rate->etoiles = $value;
        $rate->save();
        $this->dispatch('alert', ['message' => "Votre note a été enregistré !", 'type' => 'success']);
    }
}
