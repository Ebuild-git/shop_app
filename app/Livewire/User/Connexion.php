<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;

class Connexion extends Component
{
    public $email, $password;
    public $error = 0;
    public $showPassword = false;


    public function render()
    {
        return view('livewire.user.connexion');
    }

    //validation strict
    protected $rules = [
        'email' => 'required|string',
        'password' => ['required', 'string']
    ];

    public function connexion()
    {
        $this->validate();

        // verifier que l'email existe si non retourner l'erreur
        $user = User::where("email", $this->email)->Orwhere('username', $this->email)->first();
        if (!$user) {
            session()->flash("error", "Cet utilisateur n'existe pas");
            $this->reset(['email', 'password']);
            return;
        }

        //verifier que le mot de passe est ok
        if (!password_verify($this->password, $user->password)) {
            session()->flash("error", "Mot de passe incorrect");
            $this->error = $this->error + 1;
            $this->password="";
            if ($this->error == 5) {
                return redirect("/forget")
                ->with("error", "Tentatives dépassées veuillez réessayer plus tard ou réinitialiser votre mot de passe si vous avez oublié !");
            }
            return;
        };

        //verifier que l'utilisateur a bien verifier son compte avec l'email de verification
        if (!$user->hasVerifiedEmail()) {
            session()->flash("info", "Veuillez vérifier votre boite mail pour activer votre compte.");
            return;
        }

        //verifer que il a le role User 
        if ($user->hasRole('admin')) {
            session()->flash("error", "Vous n'avez pas l'autorisation de vous connecter");
            return;
        }

        if($user->locked == true){
            session()->flash("error","Compte bloqué. Veuillez contacter un administrateur pour réactiver votre compte.");
            return;
        }


        //connecter l'utilisateur
        auth()->login($user);

        return redirect('/');
    }
}
