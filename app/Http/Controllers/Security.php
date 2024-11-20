<?php

namespace App\Http\Controllers;

use App\Models\sous_categories;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Security extends Controller
{




    public function verify_account($id_user, $token)
    {
        try {
            $user = User::findOrFail($id_user);
            // Vérification du token
            if ($token != $user->remember_token) {
                return view('User.verifiy')->with("error", "Token invalide");
            } else {
                $user->email_verified_at = Now();
                $user->save();
                return redirect('/connexion')->with("success", "Votre compte a été verifié , vous pouvez desormais vous connecter !");
            }
        } catch (ModelNotFoundException $e) {
            return view('User.verifiy')->with("error", "Utilisateur introuvable");
        }
    }


    public function reset_password($token)
    {
        try {
            $user = User::where("remember_token", $token)->first();
            // Vérification du token

            //check token time generation
            if (now()->diffInMinutes($user->updated_at) > 20) {
                return view('User.Auth.reset_password')
                    ->with("message", "Le délai pour réinitialiser votre mot de passe est dépassé. Veuillez réessayer.");
            } else {
                return view('User.Auth.reset_password')
                    ->with(["user" => $user])
                    ->with("success", "ok");
            }
        } catch (ModelNotFoundException $e) {
            return view('User.Auth.reset_password')
                ->with("message", "Utilisateur introuvable ou token invalide");
        }
    }
}
