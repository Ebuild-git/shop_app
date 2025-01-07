<?php

namespace App\Livewire\User;

use App\Models\User;
use App\Models\UserCart;
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

        // Verify that the email exists, if not return an error
        $user = User::where("email", $this->email)
                    ->orWhere('username', $this->email)
                    ->first();

        if (!$user) {
            session()->flash("error", "Cet utilisateur n'existe pas");
            $this->reset(['email', 'password']);
            return;
        }

        // Verify that the password is correct
        if (!password_verify($this->password, $user->password)) {
            session()->flash("error", "Mot de passe incorrect");
            $this->error++;
            $this->password = "";

            if ($this->error == 5) {
                return redirect("/forget")
                    ->with("error", "Tentatives dépassées. Veuillez réessayer plus tard ou réinitialiser votre mot de passe.");
            }

            return;
        }

        if (!$user->hasVerifiedEmail()) {
            session()->flash("info", "Veuillez vérifier votre boite mail pour activer votre compte.");
            return;
        }

        if ($user->hasRole('admin')) {
            session()->flash("error", "Vous n'avez pas l'autorisation de vous connecter.");
            return;
        }

        if ($user->locked == true) {
            session()->flash("error", "Compte bloqué. Veuillez <a href='/contact'>cliquer ici</a> pour contacter un administrateur et réactiver votre compte.");
            return;
        }
        $user->update(['last_login_at' => now()]);
        auth()->login($user);
        $savedCart = UserCart::where('user_id', $user->id)->get();
        $cart = [];

        foreach ($savedCart as $item) {
            $cart[] = ['id' => $item->post_id];
        }
        setcookie('cart', json_encode($cart), time() + (86400 * 30), '/');
        return redirect('/');
    }

}
