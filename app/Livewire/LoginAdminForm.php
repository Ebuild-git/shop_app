<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class LoginAdminForm extends Component
{
    public $email, $password;


    public function render()
    {
        return view('livewire.login-admin-form');
    }

    public function connexion(Request $request)
    {
        $validatedData = $this->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ], [
            'required' => 'Ce champ est obligatoire.',
            'email' => 'Veuillez entrer une adresse email valide.',
            'unique' => 'Cette valeur est déjà utilisée.',
        ]);


        $user = User::where('email', $this->email)
            ->where("role", "admin")
            ->first();
        if (!$user) {
            session()->flash('error', 'Cet e-mail n\'existe pas autorisé!');
        }
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            return redirect('/dashboard');
        } else {
            session()->flash('error', 'Echec de connexion');
            //$this->reset('password');
        }
    }
}
