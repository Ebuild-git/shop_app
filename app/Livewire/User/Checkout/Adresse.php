<?php

namespace App\Livewire\User\Checkout;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\regions;
use App\Models\User;
use App\Models\City;

class Adresse extends Component
{
    public $user;

    public $region;
    public $regions;
    public $rue;
    public $nom_batiment;
    public $etage;
    public $num_appartement;
    public $phone_number;

    public $next = false;
    public $locationUsed = false;

    public $cities = [];
    public $city_id;

    public function mount()
    {
        $this->user = Auth::user();
        $this->region = $this->user->region;
        $this->rue = $this->user->rue;
        $this->nom_batiment = $this->user->nom_batiment;
        $this->etage = $this->user->etage;
        $this->num_appartement = $this->user->num_appartement;
        $this->phone_number = $this->user->phone_number;
        $this->city_id = $this->user->city_id;
        $this->cities  = City::orderBy('name')->get();
        $this->regions = regions::all();
    }

    protected $listeners = ['storeLocation' => 'storeLocation'];

    protected $rules = [
        'region' => 'required|exists:regions,id',
        'rue' => 'required|string|max:255',
        'nom_batiment' => 'required|string|max:255',
    ];

    public function storeLocation($city)
    {
        $user = User::find(Auth::id());
        $user->city = $city;
        $user->save();

        $this->user = $user;

        session()->flash('success', 'Adresse mise à jour avec succès!');
        return Redirect("/checkout?step=2");
    }

    public function updateAddress()
    {
        $this->validate();

        $this->user->region = $this->region;
        $this->user->rue = $this->rue;
        $this->user->nom_batiment = $this->nom_batiment;
        $this->user->etage = $this->etage;
        $this->user->num_appartement = $this->num_appartement;
        $this->user->phone_number = $this->phone_number;
        $this->user->city_id = $this->city_id;
        $this->user->save();

        $this->dispatch('addressUpdated');

        return Redirect("/checkout?step=2");
    }

    public function render()
    {
        return view('livewire.user.checkout.adresse');
    }

    public function valider()
    {
        return Redirect("/checkout?step=3");
    }
}
