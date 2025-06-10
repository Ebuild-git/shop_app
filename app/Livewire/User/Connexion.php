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
            session()->flash("error", __("auth.user_not_found"));
            $this->reset(['email', 'password']);
            return;
        }

        // Verify that the password is correct
        if (!password_verify($this->password, $user->password)) {
            session()->flash("error", __("auth.incorrect_password"));
            $this->error++;
            $this->password = "";

            if ($this->error == 5) {
                return redirect("/forget")
                    ->with("error", __("auth.too_many_attempts"));
            }

            return;
        }

        if (!$user->hasVerifiedEmail()) {
            session()->flash("info", __("auth.verify_email"));
            return;
        }

        if ($user->hasRole('admin')) {
            session()->flash("error", __("auth.no_permission"));
            return;
        }

        if ($user->locked == true) {
            session()->flash("error", __("auth.account_locked"));
            return;
        }
        $user->update(['last_login_at' => now()]);
        auth()->login($user);

        $locale = session('locale') ?? request()->cookie('locale');
        if (in_array($locale, ['en', 'fr', 'ar']) && $user->locale !== $locale) {
            $user->update(['locale' => $locale]);
        }


        $savedCart = UserCart::where('user_id', $user->id)->get();
        $cart = [];

        foreach ($savedCart as $item) {
            $cart[] = ['id' => $item->post_id];
        }
        setcookie('cart', json_encode($cart), time() + (86400 * 30), '/');
        return redirect('/');
    }

}
