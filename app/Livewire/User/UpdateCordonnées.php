<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class UpdateCordonnées extends Component
{
    public $rib_number;
    public $bank_name;
    public $titulaire_name;

    public function mount()
    {
        $user = User::find(Auth::id());
        if ($user) {
            // Load the user's existing data into the component properties
            $this->rib_number = $user->rib_number ? Crypt::decryptString($user->rib_number) : '';
            $this->bank_name = $user->bank_name;
            $this->titulaire_name = $user->titulaire_name;
        }
    }

    public function render()
    {
        return view('livewire..user.update-cordonnées');
    }

    public function update()
    {
        $this->validate([
            'rib_number' => 'required|string|min:13|max:32',
            'bank_name' => 'required|string',
            'titulaire_name' => 'required|string',
        ],[
            'rib_number.required' => 'Veuillez saisir votre numéro de RIB',
            'rib_number.min' => 'Votre numéro de RIB doit contenir au moins 13 caractères',
            'rib_number.max' => 'Votre numéro de RIB ne peut pas dépasser 24 caractères',
            'bank_name.required' => 'Veuillez saisir le nom de la banque',
            'titulaire_name.required' => 'Veuillez saisir le nom du titulaire du compte',
        ]);

        $user = User::find(Auth::id());
        if ($user) {
            $user->rib_number = Crypt::encryptString($this->rib_number);
            $user->bank_name = $this->bank_name;
            $user->titulaire_name = $this->titulaire_name;
            $user->save();
            $this->dispatch('alert', ['message' => "Informations mises à jour avec succès !", 'type' => 'info']);
            $this->dispatch('refreshAlluser-information');
        }else {
            $this->dispatch('alert', ['message' => "Une erreur est survenue !", 'type' => 'warning']);
        }
    }

}
