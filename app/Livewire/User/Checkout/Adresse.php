<?php

namespace App\Livewire\User\Checkout;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Adresse extends Component
{
    public $user;
    public $next = false;

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
        if( $this->user->address &&  $this->user->phone_number &&  $this->user->region ){
            $this->next = true;
        }
        return view('livewire.user.checkout.adresse');
    }


    public function valider(){
        return Redirect("/checkout?step=3");
    }
}
