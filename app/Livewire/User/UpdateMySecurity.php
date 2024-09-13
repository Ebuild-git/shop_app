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
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ]
        ],[
            'old_password.required' => 'Veuillez saisir votre ancien mot de passe',
            'password.required' => 'Veuillez saisir votre nouveau mot de passe',
            'password.confirmed' => 'La confirmation du nouveau mot de passe ne correspond pas au nouveau mot de passe',
            'password.min' => 'Votre nouveau mot de passe doit contenir au moins 8 caractères',
            'password.regex' => 'Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial(-!@# etc.).',
        ]);

        $user = User::find(Auth::id());
        if ($user) {
            //verifier si le mot de passe actuelle est le meme que $old_password
            if (Hash::check($this->old_password, $user->password)) {
                //si c'est le cas on peut modifier le password
                $user->password = Hash::make($this->password);
                $user->save();
                session()->flash('success', 'Votre mot de passe a été mis à jour avec succès!');
                $this->dispatch('alert', ['message' => "Votre mot de passe a été mis à jour avec succès!", 'type' => 'info']);
            } else {
                session()->flash('error', 'Le mot de passe actuel fourni est incorrecte!');
            };
        }
    }
}
