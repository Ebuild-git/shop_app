<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\NewPassword;
use App\Mail\VerifyMail;
use App\Models\regions;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'reset_password','delete_email']]);
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
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], 200);
    }




    public function regions(){
        $regions=regions::all();
        return response()->json($regions);
    }



    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'string'],
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'matricule' => 'nullable|mimes:jpg,png,jpeg,pdf|max:2048',
            'nom' => ['required', 'string'],
            'telephone' => ['required', 'numeric'],
            'username' => "required|string|unique:users,username"
        ]);

        // Si la validation échoue, retourner les erreurs sous forme de réponse JSON
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ], 400); // 422 Unprocessable Entity
        }

        //generer un token pour la verification de mail
        $token = md5(time());

        $user = new User();
        if ($request->photo) {
            $newName = $request->photo->store('uploads/avatars', 'public');
            $user->avatar = $newName;
        }
        $user->name = $request->nom;
        $user->email = $request->email;
        $user->password =  Hash::make($request->password);
        $user->phone_number = $request->telephone;
        $user->role = "user";
        $user->type = "user";
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
                'message' => 'Votre compte a bien été créé. Nous vous avons envoyé un email pour valider votre adresse e-mail.'
            ],
            201
        );
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


    public function reset_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        // Si la validation échoue, retourner les erreurs sous forme de réponse JSON
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->all()
            ], 400);
        }

        $user = User::where("email", $request->email)->first();
        if ($user) {
            //generer un token pour la verification de mail
            $token = md5(time());
            $user->remember_token = $token;
            $user->updated_at = now();
            $user->save();

            // Send an email with the new generated password to the user
            Mail::to($request->email)->send(new NewPassword($token, $user));
            return response()->json(
                ['message' => "Un lien de réinitialisation a été envoyé à votre adresse e-mail."],
                200

            );
        } else {
            return response()->json(
                [
                    'message' => 'Cette adresse n\'est pas associée à un compte.'
                ],
                401
            );
        }
    }




    public function delete_email(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->all()
            ], 400);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->delete();
            return response()->json(
                ['message' => "L'adresse email a été supprimé !."],
                200

            );
        } else {
            return response()->json(
                [
                    'message' => 'Cette adresse n\'est pas associée à un compte.'
                ],
                401
            );
        }
    }
}
