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

    protected $rules = [
        'lastname' => 'required|string|max:100',
        'firstname' => 'required|string|max:100',
        'username' => 'required|string|max:100',
        'email' => 'required|email|max:100',
        'phone_number' => ['nullable', 'numeric','max:100'],
        'region' => 'required|integer|exists:regions,id',
        'address' => 'string|nullable|max:255',
        'avatar' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048'
    ];

    public function update()
    {

        $this->validate();

        //verifier en fonction de la date que l'utilisateur a minimun 13 ans et maximun 100 ans
        $dateString = $this->annee . "-" . $this->mois . "-" . $this->jour;
        $date = date_create_from_format('Y-m-d', $dateString);
        if ($date === false) {
            $this->addError('jour', 'Format de date incorrect');
            return;
        }
        // Calculer la différence entre l'année actuelle et l'année fournie
        $currentYear = date('Y');
        $yearOfBirth = (int) $date->format('Y');
        $age = $currentYear - $yearOfBirth;

        if ($age < 13) {
            $this->addError('jour', 'L\'âge minimal est de 13 ans');
            return;
        }



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
            } else {
                $user->photo_verified_at = now();
            }
        }

        $user->lastname = $this->lastname;
        $user->prenom = $this->prenom;
        $user->username = $this->username;
        $user->phone_number = $this->phone_number;
        $user->region = $this->region;
        $user->address = $this->address;
        $user->birthdate = $date;
        $user->save();

        session()->flash('info', 'Informations mises à jour avec succès !');

        $this->dispatch('refreshAlluser-information');
    }
}
