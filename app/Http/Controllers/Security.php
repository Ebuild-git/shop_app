<?php

namespace App\Http\Controllers;

use App\Models\sous_categories;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Security extends Controller
{


    public function corrction()
    {
        $enregistrements = sous_categories::where('required',null)->get();
        foreach ($enregistrements as $sous) {
            $s = sous_categories::find($sous->id);
            if ($s) {
                $convert = [];
                foreach ($s ?? [] as $pro) {
                    $convert[] =
                        [
                            'id' => $pro,
                            'required' => 'Non'
                        ];
                    // convert $convert to collection
                    $coll = collect($convert);
                }
                $s->required = $coll;
                $s->save();
                echo "fais  <br> ";
            }
        }

        echo "tout est ok";
    }


    
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
                return view('User.Auth-user.reset_password')
                    ->with("message", "token expirer");
            } else {
                return view('User.Auth-user.reset_password')
                    ->with(["user" => $user])
                    ->with("success", "ok");
            }
        } catch (ModelNotFoundException $e) {
            return view('User.Auth-user.reset_password')
                ->with("message", "Utilisateur introuvable ou token invalide");
        }
    }
}
