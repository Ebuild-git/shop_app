<?php

namespace App\Livewire;

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
        ;
        return view('livewire.header-user-profil-admin');
    }

    public function decertifier()
    {
        $this->user->certifier = "non";
        $this->user->save();
        $this->dispatch('alert', ['message' => "Certification retiré !",'type'=>'warning']);
    }
    public function certifier()
    {
        $this->user->certifier = "oui";
        $this->user->save();
        $this->dispatch('alert', ['message' => "Certification ajouté !",'type'=>'success']);
    }


    public function photo()
    {
        if (is_null($this->user->photo_verified_at)) {
            $this->user->photo_verified_at = now();
            $this->dispatch('alert', ['message' => "Photo accepté !",'type'=>'info']);
        } else {
            $this->user->photo_verified_at = null;
            $this->dispatch('alert', ['message' => "La Photo a été refusée !","type"=>"info"]);
        }
        $this->user->save();
    }
}
