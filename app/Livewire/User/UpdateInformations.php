<?php

namespace App\Livewire\User;

use App\Events\AdminEvent;
use App\Models\configurations;
use App\Models\notifications;
use App\Models\regions;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;


class UpdateInformations extends Component
{
    use WithFileUploads;
    public $lastname, $email, $phone_number, $ville, $region, $avatar, $address, $username, $firstname, $jour, $mois, $annee;


    public function mount()
    {
        $user = User::find(Auth::id());
        $this->email = $user->email;
        $this->lastname = $user->lastname;
        $this->ville = $user->ville;
        $this->region = $user->region;
        $this->address = $user->address;
        $this->username = $user->username;
        $this->firstname = $user->firstname;
        $this->phone_number = $user->phone_number;
        $date = Carbon::parse($user->birthdate);
        $this->jour = $date->day;
        $this->mois = $date->month;
        $this->annee = $date->year;

    }

    public function render()
    {
        $regions = regions::all(["id", "nom"]);
        return view('livewire.user.update-informations', compact('regions'));
    }

    public function updatedUsername($value)
    {
        $cleanedUsername = preg_replace('/[^A-Za-z0-9\-#]/', '', $value);
        $this->username = $cleanedUsername;
    }


    public function update()
    {
        $this->validate([
            'lastname' => 'required|string|max:100',
            'firstname' => 'required|string|max:100',
            'username' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone_number' => ['nullable', 'string'],
            'region' => 'nullable|integer|exists:regions,id',
            'address' => 'string|nullable|max:255',
            'avatar' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048'
        ],[
            "required" => "Veuillez rensigner ce champs",
            "veuillez entrer une valeur de type texte",
            "string" => "Veuillez entrer une valeur de type texte",
            "max" => "Veuillez choisir une image de maximnu 2048",
            "mimes" => "Veuillez choisir une image de type jpg, png, jpeg, webp",
            "email" => "Veuillez entrer une adresse email",
        ]);


        $date = \Carbon\Carbon::createFromDate( $this->annee, $this->mois, $this->jour);
        $age = $date->diffInYears(\Carbon\Carbon::now());
        if ($age < 13) {
            $this->addError('jour', 'Vous devez doit être âgé d\'au moins 13 ans');
            return;
        }



        $user = User::find(Auth::user()->id);

        if ($this->email != Auth::user()->email) {
            $existingEmail = User::where('email', $this->email)->first();
            if ($existingEmail) {
                $this->addError('email', 'Cet email existe déja!');
            } else {
                $user->email = $this->email;
            }
        }
        if ($this->avatar) {
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
            } else {
                $user->photo_verified_at = now();
            }
        }

        $user->lastname = $this->lastname;
        $user->firstname = $this->firstname;
        $user->username = $this->username;
        $user->phone_number =  str_replace(' ', '', $this->phone_number);
        $user->region = $this->region;
        $user->address = $this->address;
        $user->birthdate = $date;
        $user->save();

        session()->flash('info', 'Informations mises à jour avec succès !');

        $this->dispatch('refreshAlluser-information');
    }
}
