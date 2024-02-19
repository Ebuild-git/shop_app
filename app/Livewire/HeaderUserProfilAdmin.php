<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class HeaderUserProfilAdmin extends Component
{
    public $user,$id;
    public function mount($id){
        $this->id = $id;
    }
    public function render()
    {
        $this->user = User::find($this->id);;
        return view('livewire.header-user-profil-admin');
    }

    public function decertifier(){
        $this->user->certifier = "non";
        $this->user->save();
    }
    public function certifier(){
        $this->user->certifier = "oui";
        $this->user->save();
    }
}
