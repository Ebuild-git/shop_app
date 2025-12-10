<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\NewPassword;
use App\Mail\VerifyCode;
use App\Mail\ReVerifyCode;
use App\Mail\SendOtpMail;
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
use App\Events\AdminEvent;
use App\Models\notifications;
use App\Models\configurations;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/login",
     *     operationId="loginUser",
     *     tags={"Auth"},
     *     summary="Login a user",
     *     description="Login using either email or username and returns a Sanctum API token. Only verified emails can log in.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"login", "password"},
     *             @OA\Property(property="login", type="string", example="user@example.com", description="Email or username"),
     *             @OA\Property(property="password", type="string", format="password", example="SecureP@ss123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Login successful."),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="lastname", type="string", example="Doe"),
     *                 @OA\Property(property="firstname", type="string", example="John"),
     *                 @OA\Property(property="username", type="string", example="johndoe"),
     *                 @OA\Property(property="email", type="string", example="user@example.com"),
     *                 @OA\Property(property="avatar", type="string", example="https://yourdomain.com/storage/uploads/avatars/avatar.jpg"),
     *                 @OA\Property(property="avatar_locked", type="boolean", example=true, description="Indicates if avatar is awaiting admin verification"),
     *                 @OA\Property(property="email_verified_at", type="string", format="date-time", example="2025-11-21T10:00:00Z")
     *             ),
     *             @OA\Property(property="token", type="string", example="11|ui0JTxoBEOIhhufA......."),
     *             @OA\Property(property="note", type="string", example="Your avatar is awaiting admin verification. You will receive a notification once it's approved.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid credentials.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Email not verified",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Your email is not verified. Please check your inbox for verification.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="errors", type="object", example={"login": {"Email or username is required."}, "password": {"Password is required."}})
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required' => 'Email or username is required.',
            'password.required' => 'Password is required.',
        ]);

        $user = User::where('email', $request->login)
                    ->orWhere('username', $request->login)
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials.'
            ], 401);
        }

        if (is_null($user->email_verified_at)) {
            return response()->json([
                'status' => false,
                'message' => 'Your email is not verified. Please check your inbox for verification.'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $userData = [
            'id' => $user->id,
            'lastname' => $user->lastname,
            'firstname' => $user->firstname,
            'username' => $user->username,
            'email' => $user->email,
            'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
            'avatar_locked' => is_null($user->photo_verified_at),
            'email_verified_at' => $user->email_verified_at,
        ];

        $response = [
            'status' => true,
            'message' => 'Login successful.',
            'user' => $userData,
            'token' => $token,
        ];

        if ($userData['avatar_locked']) {
            $response['note'] = "Your avatar is awaiting admin verification. You will receive a notification once it's approved.";
        }

        return response()->json($response);
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     operationId="registerUser",
     *     tags={"Auth"},
     *     summary="Register a new user",
     *     description="Registers a new user and sends a 6-digit verification code to the user's email. Returns basic user info and avatar lock status.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={
     *                     "email", "password", "password_confirmation", "nom", "prenom",
     *                     "adresse", "telephone", "username", "genre", "jour", "mois", "annee",
     *                     "ruee", "nom_batiment"
     *                 },
     *                 @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *                 @OA\Property(property="password", type="string", format="password", example="SecureP@ss123"),
     *                 @OA\Property(property="password_confirmation", type="string", format="password", example="SecureP@ss123"),
     *                 @OA\Property(property="nom", type="string", example="Doe"),
     *                 @OA\Property(property="prenom", type="string", example="John"),
     *                 @OA\Property(property="avatar", type="string", format="binary", nullable=true),
     *                 @OA\Property(property="adresse", type="string", example="Casablanca"),
     *                 @OA\Property(property="telephone", type="string", example="+212612345678"),
     *                 @OA\Property(property="username", type="string", example="johndoe"),
     *                 @OA\Property(property="genre", type="string", enum={"male","female"}, example="male"),
     *                 @OA\Property(property="jour", type="integer", example=15),
     *                 @OA\Property(property="mois", type="integer", example=6),
     *                 @OA\Property(property="annee", type="integer", example=2000),
     *                 @OA\Property(property="ruee", type="string", example="Rue Zerktouni"),
     *                 @OA\Property(property="nom_batiment", type="string", example="Batiment A"),
     *                 @OA\Property(property="etage", type="string", nullable=true, example="3"),
     *                 @OA\Property(property="num_appartement", type="string", nullable=true, example="12B")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Account created successfully."),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="lastname", type="string", example="Doe"),
     *                 @OA\Property(property="firstname", type="string", example="John"),
     *                 @OA\Property(property="username", type="string", example="johndoe"),
     *                 @OA\Property(property="email", type="string", example="user@example.com"),
     *                 @OA\Property(property="avatar", type="string", example="https://example.com/storage/uploads/avatars/avatar1.png", nullable=true),
     *                 @OA\Property(property="avatar_locked", type="boolean", example=true, description="True if avatar is pending admin verification"),
     *                 @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true, example=null)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={
     *                     "email": {"This email is already used."},
     *                     "username": {"This username is already taken."}
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Forbidden word in input or age restriction",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="The word 'shopin' is not allowed in the field 'email'.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Email sending error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unable to send verification email."),
     *             @OA\Property(property="error", type="string", example="SMTP connection failed")
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        $forbiddenWord = 'shopin';

        $forbiddenFields = ['email', 'username', 'nom', 'prenom'];
        foreach ($forbiddenFields as $field) {
            if ($request->filled($field) && stripos($request->$field, $forbiddenWord) !== false) {
                return response()->json([
                    'status' => false,
                    'message' => "The word '$forbiddenWord' is not allowed in the field '$field'."
                ], 422);
            }
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ],
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'avatar' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'adresse' => 'required|string',
            'telephone' => 'required|string|max:15',
            'username' => 'string|unique:users,username',
            'genre' => 'required|in:female,male',
            'jour' => 'required|integer|between:1,31',
            'mois' => 'required|integer|between:1,12',
            'annee' => 'required|integer|between:1950,2024',

            'ruee' => 'required|string',
            'nom_batiment' => 'required|string',
            'etage' => 'nullable|string',
            'num_appartement' => 'nullable|string',
        ], [
            'email.unique' => 'This email is already used.',
            'username.unique' => 'This username is already taken.',
            'email.required' => 'Email is required.',
            'username.required' => 'Username is required.',
            'email.email' => 'Email format is invalid.',
            'password.confirmed' => 'Passwords do not match.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, a number, and a special character (-!@# etc.)'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $birthdate = Carbon::createFromDate($request->annee, $request->mois, $request->jour);
        if ($birthdate->diffInYears(now()) < 18) {
            return response()->json([
                'status' => false,
                'message' => "You must be at least 18 years old."
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

        if ($request->hasFile('avatar')) {
            $user->avatar = $request->avatar->store('uploads/avatars', 'public');
            $avatarUploaded = true;
        } else {
            $avatarUploaded = false;
        }

        $config = configurations::first();
        $user->photo_verified_at = ($config->valider_photo == 1) ? null : now();
        $user->verification_code = rand(100000, 999999);
        $user->save();
        $user->assignRole('user');

        event(new AdminEvent("Un nouvel utilisateur s'est inscrit."));
        $notification = new notifications();
        $notification->type = "photo";
        $notification->titre = "Nouvel utilisateur : " . $user->username;
        $notification->url = "/admin/client/" . $user->id . "/view";
        $notification->message = "Un nouveau compte a été créé";
        $notification->id_user = $user->id;
        $notification->destination = "admin";
        $notification->save();

        if ($avatarUploaded) {
            event(new AdminEvent('Un utilisateur a ajouté une photo de profil'));

            $notif2 = new notifications();
            $notif2->type = "photo";
            $notif2->titre = $user->username . " vient d'ajouter sa photo de profil";
            $notif2->url = "/admin/client/" . $user->id . "/view";
            $notif2->message = "Le client a ajouté sa photo de profil";
            $notif2->id_user = $user->id;
            $notif2->destination = "admin";
            $notif2->save();
        }

        try {
            Mail::to($user->email)->send(new VerifyCode($user, $user->verification_code));
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => "Unable to send verification email.",
                'error' => $e->getMessage(),
            ], 500);
        }

        $userData = [
            'id' => $user->id,
            'lastname' => $user->lastname,
            'firstname' => $user->firstname,
            'username' => $user->username,
            'email' => $user->email,
            'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
            'avatar_locked' => is_null($user->photo_verified_at),
            'email_verified_at' => $user->email_verified_at,
        ];
        return response()->json([
            'status' => true,
            'message' => "Account created successfully.",
            'user' => $userData,
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/verify-code",
     *     operationId="verifyUserCode",
     *     tags={"Auth"},
     *     summary="Verify user email",
     *     description="Verify a newly registered user's email using the code sent by email.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "code"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="code", type="string", example="123456", description="6-digit verification code")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email verified successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Email verified successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid verification code",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid verification code.")
     *         )
     *     )
     * )
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|digits:6'
        ]);

        $user = User::where('email', $request->email)
                    ->where('verification_code', $request->code)
                    ->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid verification code.'
            ], 422);
        }

        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Email verified successfully.'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     operationId="logoutUser",
     *     tags={"Auth"},
     *     summary="Logout the authenticated user",
     *     description="Revokes the current Sanctum API token used for authentication.",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Logged out successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully.'
        ]);
    }

       /**
     * @OA\Post(
     *     path="/api/request-password-reset",
     *     summary="Request a password reset OTP",
     *     description="Send a One-Time Password (OTP) to the user's email address for resetting their password.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An OTP has been sent to your email."),
     *             @OA\Property(property="token", type="string", example="1a2b3c4d5e6f7g8h9i0j1k2l3m4n5o6p")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object", example={"email": {"The email field is required."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User not found")
     *         )
     *     )
     * )
     */

    public function requestPasswordReset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where("email", $request->email)->first();

        if ($user) {
            $otp = random_int(100000, 999999);
            $token = Str::random(32);
            $user->otp = $otp;
            $user->otp_token = $token;
            $user->otp_expires_at = now()->addMinutes(10);
            $user->save();

            Mail::to($user->email)->send(new SendOtpMail($otp));

            return response()->json([
                'message' => 'An OTP has been sent to your email.',
                'token' => $token
            ], 200);

        }

        return response()->json(['error' => 'User not found'], 404);
    }

        /**
     * @OA\Post(
     *     path="/api/verify-otp",
     *     summary="Verify OTP for password reset",
     *     description="Verifies the OTP sent to the user for password reset.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"otp", "token"},
     *             @OA\Property(property="otp", type="string", example="123456"),
     *             @OA\Property(property="token", type="string", example="randomGeneratedTokenString")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP verified successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="OTP verified successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid or expired OTP",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid or expired OTP")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object", example={"otp": {"The OTP field is required."}, "token": {"The token field is required."}})
     *         )
     *     )
     * )
     */

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('otp_token', $request->token)->first();

        if (!$user || $user->otp !== $request->otp || now()->greaterThan($user->otp_expires_at)) {
            return response()->json(['error' => 'Invalid or expired OTP'], 401);
        }

        return response()->json(['message' => 'OTP verified successfully.'], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/reset-password",
     *     summary="Reset password with OTP",
     *     description="Reset the user's password using the OTP token sent to their email.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"token", "password", "password_confirmation"},
     *             @OA\Property(property="token", type="string", example="randomGeneratedOtpToken"),
     *             @OA\Property(property="otp", type="string", example="randomGeneratedOtp"),
     *             @OA\Property(property="password", type="string", format="password", example="newpassword123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="newpassword123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Password reset successfully. You can now log in with your new password.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error.",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object", example={"password": {"The password must be at least 8 characters."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid or expired OTP token.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid or expired OTP token")
     *         )
     *     )
     * )
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('otp_token', $request->token)->first();

        if (!$user || now()->greaterThan($user->otp_expires_at)) {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }

        $user->password = Hash::make($request->password);
        $user->otp = null;
        $user->otp_token = null;
        $user->otp_expires_at = null;
        $user->save();

        return response()->json(['message' => 'Password reset successfully. You can now log in with your new password.'], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/resend-otp",
     *     summary="Resend OTP for password reset",
     *     description="Resends a new OTP to the user's email address for password reset.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="New OTP sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="A new OTP has been sent to your email."),
     *             @OA\Property(property="token", type="string", example="1a2b3c4d5e6f7g8h9i0j1k2l3m4n5o6p")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object", example={"email": {"The email field is required."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User not found")
     *         )
     *     )
     * )
     */
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where("email", $request->email)->first();

        if ($user) {
            $otp = random_int(100000, 999999);
            $token = Str::random(32);
            $user->otp = $otp;
            $user->otp_token = $token;
            $user->otp_expires_at = now()->addMinutes(10);
            $user->save();

            Mail::to($user->email)->send(new SendOtpMail($otp));

            return response()->json([
                'message' => 'A new OTP has been sent to your email.',
                'token' => $token
            ], 200);
        }

        return response()->json(['error' => 'User not found'], 404);
    }

    /**
     * @OA\Post(
     *     path="/api/resend-email-verification",
     *     summary="Resend email verification token",
     *     description="Resends a new email verification token to the user's email address.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Verification email sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Verification email sent successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object", example={"email": {"The email field is required."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="User not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Email already verified",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Email is already verified")
     *         )
     *     )
     * )
     */
    public function resendEmailVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        if (!is_null($user->email_verified_at)) {
            return response()->json([
                'status' => false,
                'message' => 'Email is already verified'
            ], 400);
        }

        $newCode = rand(100000, 999999);
        $user->verification_code = $newCode;
        $user->save();

        try {
            Mail::to($user->email)->send(new ReVerifyCode($user, $newCode));
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unable to send verification email.',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => true,
            'message' => 'Verification email resent successfully.'
        ], 200);
    }


    /**
     * @OA\Delete(
     *     path="/api/users/{userId}",
     *     summary="Delete a user account",
     *     description="Soft-deletes a user, anonymizes email and username, and notifies administrators.",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="ID of the user to delete",
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Utilisateur supprimé avec succès.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="User not found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Impossible de supprimer cet utilisateur.")
     *         )
     *     )
     * )
     */
    public function delete(Request $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);

            $authUser = $request->user();
            $isCurrentUser = $authUser->id === $user->id;

            $username = $user->username;
            $userPk   = $user->id;

            $user->email_deleted = $user->email;
            $user->username_deleted = $username;

            $user->email = null;
            $user->username = null;
            $user->save();

            $user->delete();

            $notification = new notifications();
            $notification->type = "new_post";
            $notification->titre = "Un utilisateur a supprimé son compte";
            $notification->url = "/admin/utilisateurs/supprime";
            $notification->message = "L'utilisateur {$username} a supprimé son compte.";
            $notification->id_user = $userPk;
            $notification->destination = "admin";
            $notification->save();

            if ($isCurrentUser) {
                $authUser->currentAccessToken()->delete();

                return response()->json([
                    'status'  => true,
                    'message' => 'Account deleted successfully',
                ], 200);
            }

            return response()->json([
                'status'  => true,
                'message' => 'User deleted successfully',
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Impossible de supprimer cet utilisateur.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


}
