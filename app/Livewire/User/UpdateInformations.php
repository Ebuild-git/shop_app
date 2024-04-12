<?php

namespace App\Livewire\User;

use App\Events\AdminEvent;
use App\Models\configurations;
use App\Models\notifications;
use App\Models\regions;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;


class UpdateInformations extends Component
{
    use WithFileUploads;
    public $name, $email, $telephone, $ville, $region, $avatar, $adress,$username,$prenom;

    public function render()
    {
        $user = User::find(Auth::id());
        $this->email = $user->email;
        $this->name = $user->name;
        $this->ville = $user->ville;
        $this->region = $user->region;
        $this->adress = $user->adress;
        $this->username = $user->username;
        $this->prenom = $user->prenom;
        $this->telephone = $user->phone_number;
        $regions = regions::all(["id", "nom"]);
        return view('livewire.user.update-informations')->with("regions", $regions);
    }

    public function updatedUsername($value)
    {
        $cleanedUsername = preg_replace('/[^A-Za-z0-9\-#]/', '', $value);
        $this->username = $cleanedUsername;
    }

    protected $rules = [
        'name' => 'required|string',
        'prenom' => 'required|string',
        'username' => 'required|string',
        'email' => 'required|email',
        'telephone' => ['nullable', 'numeric'],
        'region' => 'required|integer|exists:regions,id',
        'adress' => 'string|nullable|max:255',
        'avatar' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048'
    ];

    public function update()
    {

        $this->validate();
        $user = User::find(Auth::user()->id);

        //verifier si l'email a ete changer si oui si cela est libre
        if ($this->email != Auth::user()->email) {
            $existingEmail = User::where('email', $this->email)->first();
            if ($existingEmail) {
                //retutn erro in email input field
                $this->addError('email', 'Cet email existe déja!');
            } else {
                $user->email = $this->email;
            }
        }
        if ($this->avatar) {
            //delete old image
            Storage::disk('public')->delete($user->avatar);

            $newName = $this->avatar->store('uploads/avatars', 'public');
            $user->avatar = $newName;


            $config = configurations::first();
            if ($config->valider_photo == 1) {
                if (!is_null($user->photo_verified_at)) {
                    // Message de succès
                    event(new AdminEvent('Un utilisateur a changé sa photo de profil'));
                    //enregistrer la notification
                    $notification = new notifications();
                    $notification->type = "photo";
                    $notification->titre = $user->name . " vient de changé sa photo de profil";
                    $notification->url = "/admin/client/" . $user->id . "/view";
                    $notification->message = "Le client a modifié sa photo de profil";
                    $notification->id_user = Auth::user()->id;
                    $notification->destination = "admin";
                    $notification->save();
                }
                $user->photo_verified_at = null;
            }else{
                $user->photo_verified_at = now();
            }
        }

        $user->name = $this->name;
        $user->prenom = $this->prenom;
        $user->username = $this->username;
        $user->phone_number = $this->telephone;
        $user->region = $this->region;
        $user->adress = $this->adress;
        $user->save();

        session()->flash('info', 'Informations mises à jour avec succès !');
        $this->dispatch('refreshAlluser-information');
    }
}
