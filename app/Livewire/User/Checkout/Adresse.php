<?php

namespace App\Livewire\User\Checkout;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\regions;

class Adresse extends Component
{
    public $user;

    public $address;
    public $region;
    public $regions;


    public $next = false;

    public function mount(){
        $this->user = Auth::user();
        $this->address = $this->user->address;
        $this->region = $this->user->region;
        $this->regions = regions::all();

    }
    protected $listeners=["UpdateUserAdresse","UpdateUserAdresse"];

    protected $rules = [
        'region' => 'required|exists:regions,id',
        'address' => 'required|string|max:255',
    ];

    public function UpdateUserAdresse($adresse){
        $this->user->address = $adresse;
        $this->user->save();
    }

    public function updateAddress()
    {
        $this->user->address = $this->address;
        $this->user->region = $this->region;
        $this->user->save();
        return Redirect("/checkout?step=2");
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
