<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;

class Reset extends Component
{
    public $user,$password,$password_confirmation;

    public function mount($user){
        $this->user = $user ;
    }

    public function render()
    {
        return view('livewire.user.reset');
    }

    protected $rules = [
        'password' => 'required|min:8',
        'password_confirmation'=> 'required|same:password'
    ];

    public function reset_password(){
        $this->validate();
        $user = User::find($this->user->id);
        if($user){
            $user->password = bcrypt($this->password)  ;
            $user->save();
            session()->flash('success', 'Password has been updated successfully!');
            return redirect("connexion");
        }
    }
}
