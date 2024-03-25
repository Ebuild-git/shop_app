<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ChangePassword extends Component
{
    public $password, $curent, $password_confirmation;
    public function render()
    {
        return view('livewire.admin.change-password');
    }


    public function change_password()
    {

        //validation with password confirmation en current password
        $this->validate([
            'curent' => 'required',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required'
        ], [
            'required' => "Ce champ est réquis !",
            "confirmed" => "La confirmation ne correspond pas au mot de passe",
            'min' => "Votre mot de passe doit contenir au moins 8 caractères"
        ]);

        if (!(Hash::check($this->curent, Auth()->user()->password))) {
            //add error to curent fild
            $this->addError("curent", "Le mot de passe actuel n'est pas correct");
            $this->reset("curent");
        } else {
            $user = User::findOrFail(Auth()->user()->id);
            $user->password = Hash::make($this->password);
            $user->save();
            Auth()->logout();
            return redirect("/login")->with("success", "Mot de passe modifié avec succès!");
        }
    }
}
