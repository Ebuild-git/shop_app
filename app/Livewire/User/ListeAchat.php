<?php

namespace App\Livewire\User;

use App\Models\posts;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;


class ListeAchat extends Component
{
    use WithPagination;

    public $date;
    public function render()
    {
        $Query = posts::where("id_user_buy", Auth::id())->select("titre", "photos", "prix", "sell_at", "id");
        if (!empty($this->date)) {
            $Query->whereDate('sell_at', $this->date);
        }
        $achats = $Query->paginate(30);
        $total = posts::where("id_user_buy", Auth::id())->count();
        return view('livewire.user.liste-achat')->with("achats", $achats)->with("total",$total);
    }

    public function filtrer()
    {
        $this->resetPage();
    }
}