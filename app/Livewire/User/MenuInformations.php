<?php

namespace App\Livewire\User;

use Livewire\Component;

class MenuInformations extends Component
{
    protected $listeners = ['refreshAlluser-information' => 'refresh'];
    public function render()
    {
        return view('livewire.user.menu-informations');
    }
}
