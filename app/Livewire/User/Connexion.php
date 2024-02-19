<?php

namespace App\Livewire\User;

use Livewire\Component;

class Connexion extends Component
{
    public $email,$password;
    public function render()
    {
        return view('livewire.user.connexion');
    }

    //validation strict
    protected $rules = [
        'email' => 'required|email|exists:users,email',
        'password' => ['required', 'string']
    ];

    public function connexion(){
        
        $this->validate();

    }
}
