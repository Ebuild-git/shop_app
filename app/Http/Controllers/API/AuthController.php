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
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'update_information', 'register', 'reset_password', 'delete_email', 'regions', 'update_password']]);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     operationId="loginUser",
     *     tags={"Auth"},
     *     summary="Authenticate a user",
     *     description="Login using email or username and password. Returns a JWT token on success.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="SecureP@ss123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Connexion réussie."),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="email", type="string", example="user@example.com"),
     *                 @OA\Property(property="username", type="string", example="johndoe")
     *             ),
     *             @OA\Property(property="token", type="string", example="jwt_token_here")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Identifiants invalides.")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Determine if login is by email or username
        $loginType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [
            $loginType => $request->email,
            'password' => $request->password,
        ];

        try {
            // Attempt to authenticate and get token
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }

            // Get authenticated user
            $user = Auth::user();

            // Verify token belongs to this user
            $tokenUser = JWTAuth::setToken($token)->authenticate();

            if (!$tokenUser || $tokenUser->id !== $user->id) {
                JWTAuth::invalidate($token);
                return response()->json([
                    'status' => false,
                    'message' => 'Token does not match user'
                ], 401);
            }

            // Verify token claims match user data
            $payload = JWTAuth::getPayload($token);
             dd($payload);
            $tokenEmail = strtolower(trim($payload->get('email')));
            $userEmail = strtolower(trim($user->email));

            if ($tokenEmail !== $userEmail) {
                JWTAuth::invalidate($token);
                return response()->json([
                    'status' => false,
                    'message' => 'Token email does not match user email'
                ], 401);
            }

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token,
                'expires_in' => JWTAuth::factory()->getTTL() * 60
            ]);

        } catch (\PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Could not create token',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/regions",
     *     summary="Récupère toutes les régions",
     *     description="Retourne la liste complète des régions disponibles.",
     *     operationId="getRegions",
     *     tags={"Régions"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des régions",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nom", type="string", example="Tunis")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur"
     *     )
     * )
     */

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


    /**
     * @OA\Post(
     *     path="/api/register",
     *     operationId="registerUser",
     *     tags={"Auth"},
     *     summary="Register a new user",
     *     description="Registers a new user and returns an access token using opensourcesaver package with claims.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={
     *                 "email", "password", "password_confirmation", "nom", "prenom",
     *                 "adresse", "telephone", "username", "genre", "jour", "mois", "annee",
     *                 "ruee", "nom_batiment"
     *             },
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="SecureP@ss123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="SecureP@ss123"),
     *             @OA\Property(property="nom", type="string", example="Doe"),
     *             @OA\Property(property="prenom", type="string", example="John"),
     *             @OA\Property(property="adresse", type="string", example="Casablanca"),
     *             @OA\Property(property="telephone", type="string", example="+212612345678"),
     *             @OA\Property(property="username", type="string", example="johndoe"),
     *             @OA\Property(property="genre", type="string", enum={"male", "female"}, example="male"),
     *             @OA\Property(property="jour", type="integer", example=15),
     *             @OA\Property(property="mois", type="integer", example=6),
     *             @OA\Property(property="annee", type="integer", example=2000),
     *             @OA\Property(property="ruee", type="string", example="Rue Zerktouni"),
     *             @OA\Property(property="nom_batiment", type="string", example="Batiment A"),
     *             @OA\Property(property="etage", type="string", nullable=true, example="3"),
     *             @OA\Property(property="num_appartement", type="string", nullable=true, example="12B"),
     *             @OA\Property(property="photo", type="string", format="binary", nullable=true),
     *             @OA\Property(property="matricule", type="string", format="binary", nullable=true),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Inscription réussie."),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="email", type="string", example="user@example.com"),
     *                 @OA\Property(property="username", type="string", example="johndoe"),
     *             ),
     *             @OA\Property(property="token", type="string", example="access_token_here")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={"email": {"The email has already been taken."}}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Forbidden word in input or age restriction",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Le mot 'shopin' est interdit dans le champ email.")
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        $year = date('Y');
        $forbiddenWord = 'shopin';
        $requestData = $request->all();

        $forbiddenFields = ['email', 'username', 'nom', 'prenom'];
        foreach ($forbiddenFields as $field) {
            if (stripos($requestData[$field], $forbiddenWord) !== false) {
                return response()->json([
                    'message' => "Le mot '$forbiddenWord' est interdit dans le champ $field."
                ], 422);
            }
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required', 'confirmed', 'string', 'min:8',
                'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'
            ],
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'matricule' => 'nullable|mimes:jpg,png,jpeg,pdf|max:2048',
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'adresse' => 'required|string',
            'telephone' => 'required|string|max:15',
            'username' => 'required|string|unique:users,username',
            'genre' => 'required|in:female,male',
            'jour' => 'required|integer|between:1,31',
            'mois' => 'required|integer|between:1,12',
            'annee' => 'required|integer|between:1950,2024',
            'ruee' => 'required|string',
            'nom_batiment' => 'required|string',
            'etage' => 'nullable|string',
            'num_appartement' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $birthdate = Carbon::createFromDate($request->annee, $request->mois, $request->jour);
        if ($birthdate->diffInYears(now()) < 13) {
            return response()->json([
                'message' => 'Vous devez avoir au moins 13 ans pour vous inscrire.'
            ], 422);
        }

        $user = new User();
        $user->lastname = $request->nom;
        $user->email = $request->email;
        $user->firstname = $request->prenom;
        $user->password = Hash::make($request->password);
        $user->phone_number = $request->telephone;
        $user->birthdate = $birthdate;
        $user->gender = $request->genre;
        $user->role = "user";
        $user->type = "user";
        $user->address = $request->adresse;
        $user->username = $request->username;
        $user->ip_address = $request->ip();
        $user->rue = $request->ruee;
        $user->nom_batiment = $request->nom_batiment;
        $user->etage = $request->etage;
        $user->num_appartement = $request->num_appartement;
        $user->photo_verified_at = now();

        if ($request->hasFile('matricule')) {
            $matricule = $request->matricule->store('uploads/documents', 'public');
            $user->type = "shop";
            $user->matricule = $matricule;
        }

        $user->save();
        $user->assignRole('user');

        $customClaims = [
            'username' => $user->username,
            'email' => $user->email,
        ];

        $token = JWTAuth::claims($customClaims)->fromUser($user);
        try {
            Mail::to($user->email)->send(new VerifyMail($user, $token));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Échec d’envoi de l’e-mail de vérification.'], 500);
        }

        return response()->json([
            'message' => 'Inscription réussie.',
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer'
            ]
        ], 201);
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
