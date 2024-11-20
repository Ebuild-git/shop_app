<?php

namespace App\Livewire\User\Checkout;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\regions;
use App\Models\UserAddress;
use App\Models\User;

class Adresse extends Component
{
    public $user;

    public $address;
    public $region;
    public $regions;
    public $rue;
    public $nom_batiment;
    public $etage;
    public $num_appartement;
    public $phone_number;

    public $next = false;
    public $locationUsed = false;

    // Extra address properties
    public $userAddresses; // Array to store user addresses
    public $extraRegion;
    public $extraCity;
    public $extraStreet;
    public $extraBuilding;
    public $extraFloor;
    public $extraApartment;
    public $extraPhoneNumber;
    public $editingAddressId = null; // Tracks if we are editing an existing address
    public $isEditMode = false; // New variable to track add or edit mode

    public function mount(){
        $this->user = Auth::user();
        $this->address = $this->user->address;
        $this->region = $this->user->region;
        $this->rue = $this->user->rue;
        $this->nom_batiment = $this->user->nom_batiment;
        $this->etage = $this->user->etage;
        $this->num_appartement = $this->user->num_appartement;
        $this->phone_number = $this->user->phone_number;
        $this->regions = regions::all();
        $this->userAddresses = UserAddress::where('user_id', $this->user->id)->get();
        $this->loadAddresses();

    }
    public function loadAddresses()
    {
        // Load user and user addresses
        $this->user = Auth::user();
        $this->userAddresses = UserAddress::where('user_id', $this->user->id)->get();
    }
    // protected $listeners=["UpdateUserAdresse","UpdateUserAdresse"];
    protected $listeners = ['storeLocation' => 'storeLocation'];


    protected $rules = [
        'region' => 'required|exists:regions,id',
        'address' => 'required|string|max:255',
        'rue' => 'required|string|max:255',
        'nom_batiment' => 'required|string|max:255',
    ];

    public function storeLocation($city)
    {
        $user = User::find(Auth::id());
        UserAddress::create([
            'user_id' => $user->id,
            'city' => $city,
            'region' => $user->region_info->id,
            'street' => null,
            'building_name' => null,
            'floor' => null,
            'apartment_number' => null,
            'phone_number' => $user->phone_number,
            'is_default' => false
        ]);

        session()->flash('success', 'Nouvelle adresse ajoutée avec succès!');
        return Redirect("/checkout?step=2");
    }

    public function updateAddress()
    {
        $this->user->address = $this->address;
        $this->user->region = $this->region;
        $this->user->rue = $this->rue;
        $this->user->nom_batiment = $this->nom_batiment;
        $this->user->etage = $this->etage;
        $this->user->num_appartement = $this->num_appartement;
        $this->user->phone_number = $this->phone_number;
        $this->user->save();
        return Redirect("/checkout?step=2");
    }

    public function setDefault($id)
    {
        UserAddress::where('user_id', $this->user->id)->update(['is_default' => false]);
        $address = UserAddress::find($id);
        $address->is_default = true;
        $address->save();

        $this->userAddresses = UserAddress::where('user_id', $this->user->id)->get();
        $this->loadAddresses();
        $this->dispatch('refreshAddresses');

    }
    public function unsetDefault($id)
    {
        // Unset the default for the selected address
        $address = UserAddress::find($id);
        $address->is_default = false;
        $address->save();

        // Refresh the list of addresses
        $this->userAddresses = UserAddress::where('user_id', $this->user->id)->get();
    }

    public function deleteAddress($id)
    {
        UserAddress::find($id)->delete();
        $this->userAddresses = UserAddress::where('user_id', $this->user->id)->get();
    }

    public function resetForm()
    {
        $this->reset(['extraRegion', 'extraCity', 'extraStreet', 'extraBuilding', 'extraFloor', 'extraApartment', 'extraPhoneNumber', 'editingAddressId']);
    }

    public function prepareForAdd()
    {
        $this->resetForm();
        $this->isEditMode = false; // Explicitly set isEditMode to false when adding a new address
    }

    public function prepareForUpdate($id)
    {
        $address = UserAddress::find($id);
        if ($address) {
            $this->extraRegion = $address->region;
            $this->extraCity = $address->city;
            $this->extraStreet = $address->street;
            $this->extraBuilding = $address->building_name;
            $this->extraFloor = $address->floor;
            $this->extraApartment = $address->apartment_number;
            $this->extraPhoneNumber = $address->phone_number;
            $this->editingAddressId = $id;
            $this->isEditMode = true; // Set isEditMode to true when editing an existing address
        }
    }


    public function saveAddress()
    {
        $this->validate([
            'extraCity' => 'required|string|max:255',  // Validate based on your requirements
        ]);

        $address = $this->editingAddressId ? UserAddress::find($this->editingAddressId) : new UserAddress();
        $address->user_id = $this->user->id;
        $address->region = $this->extraRegion;
        $address->city = $this->extraCity;
        $address->street = $this->extraStreet;
        $address->building_name = $this->extraBuilding;
        $address->floor = $this->extraFloor;
        $address->apartment_number = $this->extraApartment;
        $address->phone_number = $this->extraPhoneNumber;
        $address->save();

        $this->resetForm();
        $this->userAddresses = UserAddress::where('user_id', $this->user->id)->get();
        return Redirect("/checkout?step=2");
    }
    public function removeDefault()
    {
        // Remove the default status from all addresses
        $this->user->addresses()->update(['is_default' => false]);

        // Reload addresses
        return Redirect("/checkout?step=2");

    }
    public function render()
    {

        if (($this->user->address && $this->user->phone_number && $this->user->region && $this->user->rue && $this->user->nom_batiment && $this->user->etage && $this->user->num_appartement && $this->user->phone_number) || $this->locationUsed) {
            $this->next = true;
        }
        return view('livewire.user.checkout.adresse', ['userAddresses' => $this->userAddresses]);
    }


    public function valider(){
        return Redirect("/checkout?step=3");
    }
}
