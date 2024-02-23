<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UpdateMySecurity extends Component
{
    public $password_confirmation, $old_password, $password;
    public function render()
    {
        return view('livewire.user.update-my-security');
    }
    protected $rules = [
        'old_password' => 'required|min:6',
        'password' => 'required|confirmed|min:6'
    ];

    public function update()
    {
        $this->validate();
        $user = User::find(Auth::id());
        if ($user) {
            //verifier si le mot de passe actuelle est le meme que $old_password
            if (Hash::check($this->old_password, $user->password)) {
                //si c'est le cas on peut modifier le password
                $user->password = Hash::make($this->password);
                $user->save();
                session()->flash('success', 'Votre mot de passe a été mis à jour avec succès!');
                return redirect("/connexion");
            } else {
                session()->flash('error', 'Le mot de passe actuel fourni est incorrecte!');
            };
        }
    }
}
