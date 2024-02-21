<?php

namespace App\Livewire\User;

use App\Mail\VerifyMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Inscription extends Component
{
    public $nom, $email, $telephone, $password;

    public function render()
    {
        return view('livewire.user.inscription');
    }


    //validation strict
    protected $rules = [
        'email' => 'required|email|unique:users,email',
        'password' => ['required', 'string'],
        'nom' => ['required', 'string'],
        'telephone' => ['required', 'numeric']
    ];


    public function inscription()
    {
        $this->validate();


        //generer un token pour la verification de mail
        $token = md5(time());

        $user = new User();
        $user->name = $this->nom;
        $user->email = $this->email;
        $user->phone_number = $this->telephone;
        $user->role = "user";
        $user->type = "user";
        $user->ip_adress = request()->ip();
        $user->remember_token =  $token;
        $user->save();

        //donner le role user
        $user->assignRole('user');

        //envoi du mail avec le lien de validation
        Mail::to($user->email)->send(new VerifyMail($user, $token));

        session()->flash("success", "Votre compte a bien été créé. Nous vous avons envoyé un email pour valider votre adresse e-mail.");

        //reset form
        $this->reset(['nom', 'email', 'password', 'telephone']);
    }
}
