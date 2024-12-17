<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class UpdateCordonnées extends Component
{
    use WithFileUploads;

    public $rib_number;
    public $bank_name;
    public $titulaire_name;
    public $cin_img;

    public function mount()
    {
        $user = User::find(Auth::id());
        if ($user) {
            // Load the user's existing data into the component properties
            $this->rib_number = $user->rib_number ? Crypt::decryptString($user->rib_number) : '';
            $this->bank_name = $user->bank_name;
            $this->titulaire_name = $user->titulaire_name;
            $this->cin_img = $user->cin_img;
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
            'cin_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ],[
            'rib_number.required' => 'Veuillez saisir votre numéro de RIB',
            'rib_number.min' => 'Votre numéro de RIB doit contenir au moins 13 caractères',
            'rib_number.max' => 'Votre numéro de RIB ne peut pas dépasser 32 caractères',
            'bank_name.required' => 'Veuillez saisir le nom de la banque',
            'titulaire_name.required' => 'Veuillez saisir le nom du titulaire du compte',
            'cin_img.image' => 'Veuillez télécharger une image valide.',
        ]);

        $user = User::find(Auth::id());
        if ($user) {
            $changes = false;

            // Check if the RIB number exists before decrypting
            if ($user->rib_number) {
                $current_rib_number = Crypt::decryptString($user->rib_number);
            } else {
                $current_rib_number = null;
            }

            // Check if the RIB number has changed
            if ($current_rib_number !== $this->rib_number) {
                $user->rib_number = Crypt::encryptString($this->rib_number);
                $changes = true;
            }
            if ($user->bank_name !== $this->bank_name) {
                $user->bank_name = $this->bank_name;
                $changes = true;
            }
            if ($user->titulaire_name !== $this->titulaire_name) {
                $user->titulaire_name = $this->titulaire_name;
                $changes = true;
            }

            if ($this->cin_img) {
                $path = $this->cin_img->store('cin_images', 'public');
                $user->cin_img = $path;
                $changes = true;
            }

            // Save changes if any
            if ($changes) {
                $user->save();
                $this->dispatch('alert', ['message' => "Informations mises à jour avec succès !", 'type' => 'info']);
            } else {
                $this->dispatch('alert', ['message' => "Aucune modification n'a été effectuée.", 'type' => 'info']);
            }

            // Refresh the user information
            $this->dispatch('refreshAlluser-information');
        } else {
            $this->dispatch('alert', ['message' => "Une erreur est survenue !", 'type' => 'warning']);
        }
    }

}
