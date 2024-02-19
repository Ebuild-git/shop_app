<?php

namespace App\Livewire;

use App\Models\posts;
use Livewire\Component;

class ListPublicaionUserProfil extends Component
{
    public $posts;

    public function mount($posts){
        $this->posts = $posts; 
    }

    public function render()
    {
        return view('livewire.list-publicaion-user-profil');
    }

}
