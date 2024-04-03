<?php

namespace App\Livewire;

use App\Events\UserEvent;
use App\Models\notifications;
use App\Models\User;
use Livewire\Component;

class HeaderUserProfilAdmin extends Component
{
    public $user, $id;

    public function mount($id)
    {
        $this->id = $id;
    }
    public function render()
    {
        $this->user = User::find($this->id);
        return view('livewire.header-user-profil-admin');
    }

    public function certifier()
    {
        if ($this->user->certifier == "non") {
            $this->user->certifier = "oui";
        } else {
            $this->user->certifier = "non";
        }
        $this->user->save();
        $this->dispatch('alert', ['message' => "Certification modifié !", 'type' => 'warning']);
    }

    public function photo()
    {
        if (is_null($this->user->photo_verified_at)) {
            $this->user->photo_verified_at = now();

            //make notification
            event(new UserEvent($this->user->id));
            $notification = new notifications();
            $notification->titre = "Votre photo de profil a été validé !";
            $notification->id_user_destination = $this->user->id;
            $notification->type = "alerte";
            $notification->destination = "user";
            $notification->message = "Nous vous informons que votre photo de profil a été validé par les administrateurs";
            $notification->save();

            $this->dispatch('alert', ['message' => "Photo accepté !", 'type' => 'info']);

        } else {
            $this->user->photo_verified_at = null;
            $this->dispatch('alert', ['message' => "La Photo a été refusée !", "type" => "info"]);
        }
        $this->user->save();
    }
}
