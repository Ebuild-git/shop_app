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


    public function update()
    {
        $this->validate([
            'old_password' => 'required|string',
            'password' => 'required|confirmed|min:8'
        ],[
            'old_password.required' => 'Veuillez saisir votre ancien mot de passe',
            'password.required' => 'Veuillez saisir votre nouveau mot de passe',
            'password.confirmed' => 'La confirmation du nouveau mot de passe ne correspond pas au nouveau mot de passe',
            'password.min' => 'Votre nouveau mot de passe doit contenir au moins 8 caractères'
        ]);

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
