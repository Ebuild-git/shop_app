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
    public $ratingSubmitted = false;

    public function mount($id_user)
    {
        $this->id_user = $id_user;
        $this->user = User::find($id_user);
        $this->last_buy = posts::where('id_user', $id_user)
            ->where('id_user_buy', Auth::id())
            ->whereIn('statut', ['livré', 'vendu'])
            ->Orderby("sell_at", "Desc")
            ->first();
        if ($this->last_buy) {
            $this->rate = ratings::where('id_user_buy', Auth::user()->id)
                ->where("id_user_sell", $this->user->id)
                ->where('id_post', $this->last_buy->id)
                ->Orderby("created_at", "Desc")
                ->first();
        }
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
        $old_rate = ratings::where('id_user_buy', Auth::user()->id)
            ->where("id_user_sell", $this->user->id)
            ->where('id_post', $this->last_buy->id)
            ->Orderby("created_at", "Desc")
            ->first();

        $value = intval($value);

        if (!isset($value) || ($value < 1 || $value > 5)) {
            session()->flash("error", __("error.rating_required"));
            return;
        }


        if (!$this->last_buy) {
            session()->flash("error", __("error.no_purchase"));
            return;
        }


        $allowedStatuts = ['livré'];
        if (!in_array($this->last_buy->statut, $allowedStatuts)) {
            session()->flash("warning", __("invalid_status_to_rate"));
            return;
        }

        if ($old_rate) {
            $this->dispatch('alert', ['message' => __("error.already_rated"), 'type' => 'warning']);
            return;
        }

        $date = Carbon::now();
        $date = $date->subDays(14);
        if ($this->last_buy->sell_at < $date) {
            session()->flash("error", __("error.too_late"));
            return;
        }

        $rate = new ratings();
        $rate->id_user_buy = Auth::user()->id;
        $rate->id_user_sell = $this->user->id;
        $rate->id_post = $this->last_buy->id;
        $rate->etoiles = $value;
        $rate->save();
        $this->ratingSubmitted = true;
        $this->mount($this->id_user);
        $this->dispatch('alert', ['message' => __("success.rating_saved"), 'type' => 'success']);
    }
}
