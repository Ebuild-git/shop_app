<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\VerifyMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Unauthorized'
                ],
                401
            );
        }

        // Récupération de l'utilisateur authentifié
        $user = Auth::user();

        // Génération du token JWT
        $token = $user->createToken($user->username)->plainTextToken;

        return response()->json([
            'user' => $user,
            'status' => true,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }




    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'string'],
            'photo' => 'required|image|mimes:jpg,png,jpeg,webp|max:2048',
            'matricule' => 'nullable|mimes:jpg,png,jpeg,pdf|max:2048',
            'nom' => ['required', 'string'],
            'telephone' => ['required', 'numeric'],
            'username' => "required|string|min:6|unique:users,username"
        ]);

        // Si la validation échoue, retourner les erreurs sous forme de réponse JSON
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ], 422); // 422 Unprocessable Entity
        }
        try {
            // Création d'un nouvel utilisateur


            if ($request->photo) {
                $newName = $request->photo->store('uploads/avatars', 'public');

                //generer un token pour la verification de mail
                $token = md5(time());

                $user = new User();
                $user->name = $request->nom;
                $user->email = $request->email;
                $user->password =  Hash::make($request->password);
                $user->phone_number = $request->telephone;
                $user->role = "user";
                $user->type = "user";
                $user->avatar = $newName;
                $user->ip_adress = request()->ip();
                $user->remember_token =  $token;
                $user->username = $request->username;

                if ($request->matricule) {
                    $matricule = $request->matricule->store('uploads/documents', 'public');
                    $user->type = "shop";
                    $user->matricule = $matricule;
                } else {
                    $user->validate_at = now();
                }
                $user->save();

                //donner le role user
                $user->assignRole('user');

                //envoi du mail avec le lien de validation
                Mail::to($user->email)->send(new VerifyMail($user, $token));
                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Votre compte a bien été créé. Nous vous avons envoyé un email pour valider votre adresse e-mail.'
                    ]
                );
            } else {
                return response()->json([
                    'status' => false,
                    'errors' => 'Vous n\'avez pas envoyé d\'image',
                ]);
            }
        } catch (Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $e
                ]
            );
        }
    }





    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => true,
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
