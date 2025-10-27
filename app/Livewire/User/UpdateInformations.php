<?php

namespace App\Livewire\User;

use App\Events\AdminEvent;
use App\Models\configurations;
use App\Models\notifications;
use App\Models\regions;
use App\Models\User;
use App\Models\posts;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;


class UpdateInformations extends Component
{
    use WithFileUploads;
    public  $email, $phone_number, $ville, $region, $avatar, $address, $jour, $mois, $annee, $rue, $nom_batiment, $etage, $num_appartement;


    public function mount()
    {
        $user = User::find(Auth::id());
        $this->email = $user->email;
        $this->ville = $user->ville;
        $this->region = $user->region;
        $this->address = $user->address;
        $this->phone_number = $user->phone_number;
        $date = Carbon::parse($user->birthdate);
        $this->jour = $date->day;
        $this->mois = $date->month;
        $this->annee = $date->year;
        $this->rue = $user->rue;
        $this->nom_batiment = $user->nom_batiment;
        $this->etage = $user->etage;
        $this->num_appartement = $user->num_appartement;

    }

    public function render()
    {
        $regions = regions::all(["id", "nom"]);
        return view('livewire.user.update-informations', compact('regions'));
    }




    public function update()
    {
        $this->validate([
            'email' => 'required|email|max:100',
            'phone_number' => 'required|string|max:14',
            'region' => 'required|integer|exists:regions,id',
            'address' => 'required|nullable|max:255',
            'rue'          => 'required|string|max:255',
            'nom_batiment' => 'required|string|max:255',
            'etage'        => 'required|string|max:255',
            'num_appartement' => 'required|string|max:255',
            'jour'         => 'required|integer|min:1|max:31',
            'mois'         => 'required|integer|min:1|max:12',
            'annee'        => 'required|integer',
            'avatar' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048'
        ],[
            'required' => __('required'),
            'string' => __('string'),
            'avatar.max' => __('avatar_max'),
            'mimes' => __('mimes'),
            'email' => __('email_validation'),
        ]);

        $date = \Carbon\Carbon::createFromDate( $this->annee, $this->mois, $this->jour);
        $age = $date->diffInYears(\Carbon\Carbon::now());
        if ($age < 13) {
            $this->addError('jour', __('must_be_13'));
            return;
        }
        $user = User::find(Auth::user()->id);

        if ($this->email != Auth::user()->email) {
            $existingEmail = User::where('email', $this->email)->first();
            if ($existingEmail) {
                $this->addError('email', __('email_already_exists'));
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
                    event(new AdminEvent('Un utilisateur a changé sa photo de profil'));
                    $notification = new notifications();
                    $notification->type = "photo";
                    $notification->titre = $user->username . " vient de changé sa photo de profil";
                    $notification->url = "/admin/client/" . $user->id . "/view";
                    $notification->message = "Le client a modifié sa photo de profil";
                    $notification->id_user = Auth::user()->id;
                    $notification->destination = "admin";
                    $notification->save();
                }
                $user->photo_verified_at = null;
                $photoValidationMessage = __('photo_validation_pending');
            } else {
                $user->photo_verified_at = now();
                $photoValidationMessage = __('photo_updated_successfully');
            }
        }

        $user->phone_number =  str_replace(' ', '', $this->phone_number);
        $user->region = $this->region;
        $user->address = $this->address;
        $user->birthdate = $date;
        $user->rue = $this->rue;
        $user->nom_batiment = $this->nom_batiment;
        $user->etage = $this->etage;
        $user->num_appartement = $this->num_appartement;
        $user->save();

        $this->dispatch('alert', ['message' => __('info_updated') . ($photoValidationMessage ?? ''), 'type' => 'info']);

        $this->dispatch('refreshAlluser-information');
    }

    public function delete($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $isCurrentUser = Auth::id() == $user->id;

            $username = $user->username;
            $userPk   = $user->id;

            $user->delete();
            $notification = new notifications();
            $notification->type = "new_post";
            $notification->titre = "Un utilisateur a supprimé son compte";
            $notification->url = "/admin/utilisateurs/supprime";
            $notification->message = "L'utilisateur {$username} a supprimé son compte.";
            $notification->id_user = $userPk;
            $notification->destination = "admin";
            $notification->save();

            session()->flash('message', 'Utilisateur supprimé avec succès !');
            if ($isCurrentUser) {
                Auth::logout();
                return redirect('/')->with('success', 'Votre compte a été supprimé.');
            }
        } catch (\Throwable $th) {
            session()->flash('error', 'Impossible de supprimer cet utilisateur !');
        }
    }

}
