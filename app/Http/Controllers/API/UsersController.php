<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Events\AdminEvent;
use App\Models\notifications;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use App\Models\configurations;

class UsersController extends Controller
{

    /**
     * @OA\Put(
     *     path="/api/user/update",
     *     summary="Update user profile",
     *     description="Update user information, profile photo, CIN image, RIB info, address, and more. Supports multipart form data.",
     *     tags={"User"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\RequestBody(
     *         required=false,
     *         description="Send only the fields you want to update. For PUT requests, send `_method=PUT`.",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="firstname", type="string", example="Hazar"),
     *                 @OA\Property(property="lastname", type="string", example="Nenni"),
     *                 @OA\Property(property="email", type="string", example="user@gmail.com"),
     *                 @OA\Property(property="phone_number", type="string", example="22334455"),
     *                 @OA\Property(property="region", type="integer", example=3),
     *                 @OA\Property(property="address", type="string", example="Rue X, Tunis"),
     *                 @OA\Property(property="rue", type="string", example="Rue Y"),
     *                 @OA\Property(property="nom_batiment", type="string", example="Immeuble A"),
     *                 @OA\Property(property="etage", type="string", example="2"),
     *                 @OA\Property(property="num_appartement", type="string", example="12"),
     *
     *                 @OA\Property(property="jour", type="integer", example=12),
     *                 @OA\Property(property="mois", type="integer", example=5),
     *                 @OA\Property(property="annee", type="integer", example=1998),
     *
     *                 @OA\Property(
     *                     property="avatar",
     *                     type="string",
     *                     format="binary",
     *                     description="Profile photo"
     *                 ),
     *
     *                 @OA\Property(
     *                     property="cin_img",
     *                     type="string",
     *                     format="binary",
     *                     description="CIN (ID card) image"
     *                 ),
     *
     *                 @OA\Property(property="rib_number", type="string", example="1234567890123"),
     *                 @OA\Property(property="bank_name", type="string", example="BIAT"),
     *                 @OA\Property(property="titulaire_name", type="string", example="Hazar Nenni"),
     *                 @OA\Property(property="voyage_mode", type="boolean"),
     *
     *                 @OA\Property(property="old_password", type="string", example="OldPass@123"),
     *                 @OA\Property(property="password", type="string", example="NewPass@123"),
     *                 @OA\Property(property="password_confirmation", type="string", example="NewPass@123"),
     *
     *                 @OA\Property(property="_method", type="string", example="PUT")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User info updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Information updated successfully"),
     *             @OA\Property(property="profile_photo_message", type="string", nullable=true, example="Profile photo updated successfully"),
     *             @OA\Property(property="cin_message", type="string", nullable=true, example="Your national identity card has been updated and is awaiting validation."),
     *
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="firstname", type="string", example="Hazar"),
     *                 @OA\Property(property="lastname", type="string", example="Nenni"),
     *                 @OA\Property(property="birthdate", type="string", example="1998-05-12"),
     *                 @OA\Property(property="email", type="string", example="user@gmail.com"),
     *                 @OA\Property(property="username", type="string", example="hazar123"),
     *                 @OA\Property(property="phone_number", type="string", example="22334455"),
     *                 @OA\Property(property="region", type="integer", example=3),
     *                 @OA\Property(property="address", type="string", example="Rue X, Tunis"),
     *                 @OA\Property(property="rue", type="string", example="Rue Y"),
     *                 @OA\Property(property="nom_batiment", type="string", example="Immeuble A"),
     *                 @OA\Property(property="etage", type="string", example="2"),
     *                 @OA\Property(property="num_appartement", type="string", example="12"),
     *                 @OA\Property(property="rib_number", type="string", example="1234567890123"),
     *                 @OA\Property(property="bank_name", type="string", example="BIAT"),
     *                 @OA\Property(property="titulaire_name", type="string", example="Hazar Nenni"),
     *                 @OA\Property(property="avatar", type="string", example="http://127.0.0.1:8000/storage/uploads/avatars/photo.webp"),
     *                 @OA\Property(property="cin_img", type="string", example="http://127.0.0.1:8000/storage/cin_images/cin.webp"),
     *                 @OA\Property(property="old_cin_images", type="string", nullable=true),
     *                 @OA\Property(property="voyage_mode", type="boolean", example=1),
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The email field must be a valid email address.")
     *         )
     *     )
     * )
     */
    public function update(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $profile_photo_message = null;
        $cin_message = null;

        $rules = [
            'email' => 'sometimes|nullable|email|max:100',
            'phone_number' => 'sometimes|nullable|string|max:8',
            'firstname' => 'sometimes|nullable|string',
            'lastname' => 'sometimes|nullable|string',
            'region' => 'sometimes|nullable|integer|exists:regions,id',
            'address' => 'sometimes|nullable|string',
            'rue' => 'sometimes|nullable|string',
            'nom_batiment' => 'sometimes|nullable|string',
            'etage' => 'sometimes|nullable|string',
            'num_appartement' => 'sometimes|nullable|string',

            'jour' => 'sometimes|nullable|integer|min:1|max:31',
            'mois' => 'sometimes|nullable|integer|min:1|max:12',
            'annee' => 'sometimes|nullable|integer',

            'avatar' => 'sometimes|image|mimes:jpg,png,jpeg,webp',
            'cin_img' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg,webp',

            'rib_number' => 'sometimes|nullable|string|min:13|max:32',
            'bank_name' => 'sometimes|nullable|string',
            'titulaire_name' => 'sometimes|nullable|string',

            'voyage_mode' => 'sometimes|nullable|integer'
        ];

        if ($request->has('password')) {
            $rules = array_merge($rules, [
                'old_password' => 'required|string',
                'password' => 'required|confirmed|string|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/'
            ]);
        }

        $validated = $request->validate($rules);

        $changes = false;

        if ($request->filled('email') && $request->email !== $user->email) {
            if (User::where('email', $request->email)->exists()) {
                return response()->json(['message' => 'This email already exists'], 422);
            }
            $user->email = $request->email;
            $changes = true;
        }


        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('uploads/avatars', 'public');
            $user->avatar = $path;

            $config = configurations::first();

            if ($config->valider_photo == 1) {
                if ($user->photo_verified_at) {
                    event(new AdminEvent('Un utilisateur a changé sa photo de profil'));

                    $notification = new notifications();
                    $notification->type = "photo";
                    $notification->titre = $user->username . " vient de changer sa photo de profil";
                    $notification->url = "/admin/client/" . $user->id . "/view";
                    $notification->message = "Le client a modifié sa photo de profil";
                    $notification->id_user = $user->id;
                    $notification->destination = "admin";
                    $notification->save();
                }
                $user->photo_verified_at = null;
                $profile_photo_message = "We inform you that your profile photo will undergo a validation process, which may take up to a maximum of 24 hours before it is approved.";


            } else {
                $user->photo_verified_at = now();
                $profile_photo_message = "Profile photo updated successfully";

            }

            $changes = true;
        }


        if ($request->filled(['jour','mois','annee'])) {
            $date = Carbon::create($request->annee, $request->mois, $request->jour);

            if ($date->diffInYears(now()) < 18) {
                return response()->json(['message' => 'You must be at least 18 years old'], 422);
            }

            $user->birthdate = $date;
            $changes = true;
        }

        foreach ([
            'firstname','lastname','phone_number','region','address','rue',
            'nom_batiment','etage','num_appartement', 'voyage_mode'
        ] as $field) {

            if ($request->has($field)) {
                $user->$field = $request->$field;
                $changes = true;
            }
        }

        if ($request->filled('rib_number')) {

            $current = $user->rib_number ? Crypt::decryptString($user->rib_number) : null;

            if ($current !== $request->rib_number) {

                $user->rib_number = Crypt::encryptString($request->rib_number);
                $changes = true;

                event(new AdminEvent("Un utilisateur a mis à jour son RIB."));

                $notification = new notifications();
                $notification->type = "rib";
                $notification->titre = $user->username . " a mis à jour son RIB.";
                $notification->url = "/admin/client/" . $user->id . "/view";
                $notification->message = "RIB en attente de vérification.";
                $notification->id_user = $user->id;
                $notification->destination = "admin";
                $notification->save();
            }
        }

        if ($request->filled('bank_name')) {
            $user->bank_name = $request->bank_name;
            $changes = true;
        }

        if ($request->filled('titulaire_name')) {
            $user->titulaire_name = $request->titulaire_name;
            $changes = true;
        }

        if ($request->hasFile('cin_img')) {

            $path = $request->file('cin_img')->store('cin_images', 'public');
            $user->cin_img = $path;
            $user->cin_approved = false;

            $changes = true;

            event(new AdminEvent("Un utilisateur a mis à jour sa carte d'identité."));

            $notification = new notifications();
            $notification->type = "photo";
            $notification->titre = $user->username . " a mis à jour sa carte d'identité.";
            $notification->url = "/admin/client/" . $user->id . "/view";
            $notification->message = "Carte d'identité en attente de validation.";
            $notification->id_user = $user->id;
            $notification->destination = "admin";
            $notification->save();
            $cin_message = "Your national identity card has been updated and is awaiting validation.";

        }

        if ($request->has('password')) {

            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json(['message' => 'old password incorrect'], 422);
            }

            $user->password = Hash::make($request->password);
            $changes = true;
        }

        if ($changes) {
            $user->save();

            return response()->json([
                "message" => "Information updated successfully",
                "profile_photo_message" => $profile_photo_message,
                "cin_message" => $cin_message,
                "user" => [
                    "id" => $user->id,
                    "firstname" => $user->firstname,
                    "lastname" => $user->lastname,
                    "birthdate" => $user->birthdate,
                    "email" => $user->email,
                    "username" => $user->username,
                    "phone_number" => $user->phone_number,
                    "region" => $user->region,
                    "address" => $user->address,
                    "rue" => $user->rue,
                    "nom_batiment" => $user->nom_batiment,
                    "etage" => $user->etage,
                    "num_appartement" => $user->num_appartement,
                    "rib_number" => $user->rib_number ? Crypt::decryptString($user->rib_number) : null,
                    "bank_name" => $user->bank_name,
                    "titulaire_name" => $user->titulaire_name,
                    "avatar" => $user->avatar ? asset('storage/'.$user->avatar) : null,
                    "cin_img" => $user->cin_img ? asset('storage/'.$user->cin_img) : null,
                    "old_cin_images" => $user->old_cin_images ? asset('storage/'.$user->old_cin_images) : null,
                    "voyage_mode" => $user->voyage_mode,
                ]
            ], 200);
        }

        return response()->json(['message' => 'No changes were made.'], 200);
    }


}
