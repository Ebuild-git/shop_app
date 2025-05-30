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
            "password.required" => __("password_required1"),
            "password.min" => __("password_min1"),
            "password.confirmed" => __("password_confirmed1"),
        ]);

        $user = User::find($this->user->id);

        if ($user) {
            $user->password = bcrypt($this->password);
            $user->save();
            return redirect("connexion")->with('success', __("success"));
        }
    }
}
