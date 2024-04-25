<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;

class Reset extends Component
{
    public $user, $password, $password_confirmation;

    public function mount($user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.user.reset');
    }

    public function reset_password()
    {
        $this->validate([
            'password' => 'required|confirmed|min:8',
        ], [
            "password.required" => "Veuillez entrer votre mot de passe",
            "password.min" => "Le mot de passe doit contenir au moins 8 caractères",
            "password.confirmed" => "Les mots de passes ne correspondent pas",
        ]);

        $user = User::find($this->user->id);

        if ($user) {
            $user->password = bcrypt($this->password);
            $user->save();
            return redirect("connexion")->with('success', 'Le mot de passe a été mis à jour avec succès !');
        }
    }
}
