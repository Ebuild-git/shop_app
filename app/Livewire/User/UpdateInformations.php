<?php

namespace App\Livewire\User;

use App\Events\AdminEvent;
use App\Models\notifications;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Traits\ListGouvernorat;


class UpdateInformations extends Component
{
    use WithFileUploads;
    use ListGouvernorat;
    public $name, $email, $telephone, $ville, $gouvernorat, $avatar, $adress;

    public function render()
    {
        $user = User::find(Auth::id());
        $this->email = $user->email;
        $this->name = $user->name;
        $this->ville = $user->ville;
        $this->gouvernorat = $user->gouvernorat;
        $this->adress = $user->adress;
        $this->telephone = $user->phone_number;
        return view('livewire.user.update-informations')
            ->with("list_gouvernorat", $this->get_list_gouvernorat());
    }

    protected $rules = [
        'name' => 'required|min:6',
        'email' => 'required|email',
        'telephone' => ['nullable', 'numeric'],
        'ville' => 'required|string|max:255',
        'gouvernorat' => 'required|string|max:255',
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

            if (!is_null($user->photo_verified_at)) {
                // Message de succès
                event(new AdminEvent('Un utilisateur a changé sa photo de profil'));
                //enregistrer la notification
                $notification = new notifications();
                $notification->type = "photo";
                $notification->titre = $user->name . " vient de changé sa photo de profil";
                $notification->url = "/admin/client/". $user->id ."/view";
                $notification->message = "Le client a modifié sa photo de profile";
                $notification->id_user = Auth::user()->id;
                $notification->destination = "admin";
                $notification->save();
            }

            $user->photo_verified_at = null;
        }

        $user->name = $this->name;
        $user->phone_number = $this->telephone;
        $user->ville = $this->ville;
        $user->gouvernorat = $this->gouvernorat;
        $user->adress = $this->adress;
        $user->save();

        session()->flash('info', 'Informations mises à jour avec succès !');
        $this->dispatch('refreshAlluser-information');
    }
}
