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
            // Generate a new password for the user and save it to the database
            $new_password = Str::random(8);
            
            // Send an email with the new generated password to the user
            Mail::to($this->email)->send(new NewPassword($new_password,$user));
                
            // Update the users password in the database
            $user->update([
                "password" => bcrypt($new_password),
            ]);
            session()->flash("success","Un nouveau mot de passe a été envoyé à votre adresse e-mail enregistrée.");

            //reset form
            $this->reset(['email']);
        }else{
            session()->flash("error","Email non trouvé");
        }
        
    }
}
