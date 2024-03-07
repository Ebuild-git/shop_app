<?php

namespace App\Livewire\User;

use App\Mail\NewPassword;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Illuminate\Support\Str;

class ResetPassword extends Component
{
    public $email;

    public function render()
    {
        return view('livewire.user.reset-password');
    }

    protected $rules = [
        'email' => 'required|email|exists:users,email',
    ];

    public function reset_password(){
        $this->validate();
        
        $user = User::where("email",$this->email)->first();
        if($user){
            //generer un token pour la verification de mail
            $token = md5(time());
            $user->remember_token = $token;
            $user->update_at = now();
            $user->save();
            
            // Send an email with the new generated password to the user
            Mail::to($this->email)->send(new NewPassword($token,$user));
                
            // Update the users password in the database
            session()->flash("success","le lien de reinitialisation a été envoyé à votre adresse e-mail.");

            //reset form
            $this->reset(['email']);
        }else{
            session()->flash("error","Email non trouvé");
        }
        
    }
}
