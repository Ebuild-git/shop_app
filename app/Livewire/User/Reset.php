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
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password'
        ], [
            "password.required" => "Veuillez entrer votre mot de passe",
            "password.min" => "Le mot de passe doit contenir au moins 8 caractères",
            "password_confirmation.required" => "Veuillez confirmer votre mot de passe",
            "password_confirmation.same" => "La confirmation ne correspond pas au mot de passe"
        ]);

        $user = User::find($this->user->id);

        if ($user) {
            $user->password = bcrypt($this->password);
            $user->save();
            return redirect("connexion")->with('success', 'Le mot de passe a été mis à jour avec succès !');
        }
    }
}
