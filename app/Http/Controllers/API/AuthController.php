<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\NewPassword;
use App\Mail\VerifyMail;
use App\Models\regions;
use App\Models\User;
use Carbon\Carbon;
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
        $this->middleware('auth:api', ['except' => ['login', 'update_information', 'register', 'reset_password', 'delete_email', 'regions', 'update_password']]);
    }





    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginType => $request->input('email'),
            'password' => $request->input('password'),
        ];

        if (!$token = Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
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







    public function regions()
    {
        $regions = regions::all();
        return response()->json($regions);
    }



    public function update_password(Request $request)
    {
        //update user password with api
        $validator = Validator::make($request->all(), [
            "oldPassword" => ['required'],
            "newPassword" => ['required', 'min:8']
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'message' => $validator->errors()
                ],
                406
            );
        };

        $user = Auth::user();

        if (!Hash::check($request['oldPassword'], $user['password'])) {
            return response()->json(['message' => 'Old Password Does Not Match Our Records']);
        } else {
            User::where('id', '=', $user['id'])->update([
                'password' => Hash::make($request['newPassword']),
            ]);
            return response()->json(['message' => 'Password Changed Successfully']);
        }
    }



    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'string', 'min:8'],
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'matricule' => 'nullable|mimes:jpg,png,jpeg,pdf|max:2048',
            'lastname' => ['required', 'string'],
            'firstname' => ['required', 'string'],
            'adress' => ['nullable', 'string'],
            'phone_number' => ['required', 'string', 'Max:14'],
            'username' => "string|unique:users,username",
            'gender' => 'required|in:female,male',
            'birthdate' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ], 400);
        }

        $token = md5(time());

        $user = new User();
        if ($request->photo) {
            $newName = $request->photo->store('uploads/avatars', 'public');
            $user->avatar = $newName;
        }
        $user->lastname = $request->lastname;
        $user->irstname = $request->irstname;
        $user->birthdate = $request->birthdate;
        $user->email = $request->email;
        $user->gender = $request->gender;
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

        $user->assignRole('user');
        Mail::to($user->email)->send(new VerifyMail($user, $token));
        return response()->json(
            [
                'message' => 'Votre compte a bien été créé. Nous vous avons envoyé un email pour valider votre adresse e-mail.'
            ],
            201
        );
    }


    public function update_information(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'email' => ['nullable', 'string', 'email:filter'],
            'phone_number' => ['required', 'numeric'],
            'birthdate' => ['required', 'date']
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::findOrFail(Auth::id());
        $user->update([
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email') ?: $user->email,
            'phone_number' => $request->input('phone_number'),
            'birthdate' => Carbon::parse($request->input('birthdate'))
        ]);

        return response()->json(
            ['message' => "La mise a jour a été effectué !"],
            200

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
