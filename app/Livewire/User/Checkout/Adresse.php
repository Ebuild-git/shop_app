<?php

namespace App\Livewire\User\Checkout;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Adresse extends Component
{
    public $user;

    public function mount(){
        $this->user = Auth::user();
    }
    protected $listeners=["UpdateUserAdresse","UpdateUserAdresse"];

    public function UpdateUserAdresse($adresse){
        $this->user->address = $adresse;
        $this->user->save();
    }

    public function render()
    {
        return view('livewire.user.checkout.adresse');
    }


    public function valider(){
        return Redirect("/checkout?step=3");
    }
}
