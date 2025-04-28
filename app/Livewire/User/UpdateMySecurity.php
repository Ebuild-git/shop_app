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
            'old_password.required' => __('old_password_required'),
            'password.required' => __('password_required'),
            'password.confirmed' => __('password_confirmed'),
            'password.min' => __('password_min'),
            'password.regex' => __('password_regex'),
        ]);

        $user = User::find(Auth::id());
        if ($user) {
            if (Hash::check($this->old_password, $user->password)) {
                $user->password = Hash::make($this->password);
                $user->save();
                session()->flash('success', 'Votre mot de passe a été mis à jour avec succès!');
                $this->dispatch('alert', ['message' => __('password_updated_successfully'), 'type' => 'info']);
            } else {
                session()->flash('error', 'Le mot de passe actuel fourni est incorrecte!');
            };
        }
    }
}
