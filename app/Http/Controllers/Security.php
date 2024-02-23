<?php

namespace App\Http\Controllers;

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
                return view('User.verifiy')->with("success", "ok");
            }
        }catch(ModelNotFoundException $e){
            return view('User.verifiy')->with("error", "Utilisateur introuvable");
        }
    }
}
