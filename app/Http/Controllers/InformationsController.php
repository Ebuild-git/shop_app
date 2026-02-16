<?php

namespace App\Http\Controllers;

use App\Events\UserEvent;
use App\Models\configurations;
use App\Models\notifications;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InformationsController extends Controller
{
    public function index()
    {
        $configuration = configurations::firstorCreate();
        return view("Admin.informations.index")
            ->with('configuration', $configuration);
    }




    public function change_picture_statut(Request $request)
    {
        $id_user = $request->input('id_user' ?? null);
        $user = User::find($id_user);
        if ($user) {
            if (is_null($user->photo_verified_at)) {
                $user->photo_verified_at = now();

                //make notification
                event(new UserEvent($user->id));
                $notification = new notifications();
                $notification->titre = "Votre photo de profil a été validé !";
                $notification->id_user_destination = $user->id;
                $notification->type = "alerte";
                $notification->destination = "user";
                $notification->message = "Nous vous informons que votre photo de profil a été validé par les administrateurs";
                $notification->save();

                // Send FCM notification
                $fcmService = app(\App\Services\FcmService::class);
                $sent = $fcmService->sendToUser(
                    $user->id,
                    "Votre photo de profil a été validé !",
                    "Nous vous informons que votre photo de profil a été validé par les administrateurs",
                    [
                        'type' => 'alerte',
                        'notification_id' => $notification->id,
                        'destination' => 'user',
                        'action' => 'photo_validated',
                    ]
                );

                if ($sent) {
                    \Log::info("FCM notification sent successfully", [
                        'user_id' => $user->id,
                        'notification_id' => $notification->id,
                        'type' => 'photo_validated'
                    ]);
                } else {
                    \Log::warning("FCM notification failed to send", [
                        'user_id' => $user->id,
                        'notification_id' => $notification->id,
                        'reason' => 'User has no FCM token or token invalid'
                    ]);
                }
            } else {
                $user->photo_verified_at = null;
            }
            $user->save();

            //return with success message
            return redirect()->back()->with("success", "Le changement a été éffectuer !");
        }
    }



    public function update_information_website(Request $request)
    {
        //validation du formulaire
        $validator = Validator::make($request->all(), [
            'email' => ['nullable', 'string'],
            'telephone' => ['nullable', 'string'],
            'tiktok' => ['nullable', 'url'],
            'adresse' => ['nullable', 'string'],
            'facebook' => ['nullable', 'url'],
            'instagram' => ['nullable', 'url'],
            'linkedin' => ['nullable', 'url'],
            'email_send_message' => ['nullable', 'email'],
            'logo' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:4048',
        ]);


        //verifier si une configuration est deja presente si c'est pas le cas creer une nouvelle
        $old_configuraion = configurations::first();
        if (!$old_configuraion) {
            $old_configuraion = new configurations();
        }

        $config = $old_configuraion::find($old_configuraion->id);

        $config->facebook = $request->facebook;
        $config->instagram = $request->instagram;
        $config->linkedin = $request->linkedin;
        $config->tiktok = $request->tiktok;
        $config->adresse = $request->adresse;
        $config->phone_number = $request->telephone;
        $config->email = $request->email;
        $config->valider_publication = $request->valider_publication ? true : false;
        $config->valider_photo = $request->valider_photo ? true : false;
        $config->email_send_message = $request->email_send_message;
        $config->save();

        //show success message
        return redirect()
            ->back()
            ->with("success", __('Information mises à jour avec succès'));
    }
}
