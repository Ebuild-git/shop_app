<?php

namespace App\Http\Controllers;

use App\Models\posts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use App\Events\UserEvent;
use App\Models\notifications;

class UserController extends Controller
{
    public function liste_utilisateurs(Request $request)
    {
        if (isset($request->type)) {
            $type = $request->type;
        } else if ($request->type != "all" && $request->type != "shop") {
            $type = "all";
        } else {
            $type = "all";
        }
        return view("Admin.clients.index")->with("type", $type);
    }

    public function liste_utilisateurs_locked(Request $request)
    {
        $type = "all";
        $locked = "yes";
        return view("Admin.clients.index")
            ->with("type", $type)
            ->with("locked", $locked);
    }
    public function liste_utilisateurs_supprime(Request $request)
    {
        $type = "all";
        $showTrashed = "yes";
        return view("Admin.clients.index")
            ->with("type", $type)
            ->with("showTrashed", $showTrashed);
    }

    public function details_user(Request $request)
    {
        $id = $request->id;
        try {
            $user = User::withTrashed()->findOrFail($id);
            $posts = Posts::where('id_user', $user->id)->orderBy('created_at', 'desc')->paginate(30);
            $decryptedRib = $user->rib_number ? Crypt::decryptString($user->rib_number) : null;
            $currentCinImg = $user->cin_img ? asset('storage/' . $user->cin_img) : null;
            $currentCinFilename = $user->cin_img ? basename($user->cin_img) : null;
            $oldCinImages = json_decode($user->old_cin_images, true) ?? [];
            $oldCinImages = array_map(fn($img) => asset('storage/' . $img), $oldCinImages);

            return view("Admin.clients.profile")
                ->with("user", $user)
                ->with("posts", $posts)
                ->with("decryptedRib", $decryptedRib)
                ->with("currentCinImg", $currentCinImg)
                ->with("currentCinFilename", $currentCinFilename)
                ->with("oldCinImages", $oldCinImages);
        } catch (\Throwable $th) {
            abort(404, "Page non trouvée");
        }
    }

    public function validatePhoto($id)
    {
        $user = User::findOrFail($id);
        $user->photo_verified_at = now();
        $user->save();

        event(new UserEvent($user->id));
        //make notification
        $notification = new notifications();
        $notification->titre = "Votre photo de profile a été validé !";
        $notification->id_user_destination = $user->id;
        $notification->type = "alerte";
        $notification->url = "/informations";
        $notification->destination = "user";
        $notification->id_user = $user->id;
        $notification->message = "Nous vous informons que votre photo de profile a été validé par les administrateurs.";
        $notification->save();

        // Send FCM notification
        $fcmService = app(\App\Services\FcmService::class);
        $sent = $fcmService->sendToUser(
            $user->id,
            "Votre photo de profile a été validé !",
            "Nous vous informons que votre photo de profile a été validé par les administrateurs.",
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

        return back()->with('success', 'La photo de profil a été validée avec succès.');
    }

    public function delete_my_post(Request $request)
    {
        $id = $request->input('id_post');
        $post = posts::where("id", $id)->where("id_user", Auth::user()->id)->first();
        if ($post) {
            //foreach image to delete
            foreach ($post->photos ?? [] as $img) {
                Storage::disk('public')->delete($img);
            }
            $post->forceDelete();
            return response()->json(
                [
                    'success' => true,
                    'message' => "L'annonce a été supprimé"
                ]
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => "Annonce introuvable !"
                ]
            );
        }
    }

    public function politique()
    {
        return view('User.politique-confidentialite');
    }

    public function cookies()
    {
        return view('User.politique-cookies');
    }

    /**
     * Lock or unlock a user account
     */
    public function toggleLock($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);

            if ($user->locked) {
                $user->update([
                    'locked' => false,
                    'locked_at' => null
                ]);
                $message = 'Compte utilisateur débloqué avec succès.';
            } else {
                $user->update([
                    'locked' => true,
                    'locked_at' => now()
                ]);
                $message = 'Compte utilisateur bloqué avec succès.';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de modifier le statut du utilisateur.'
            ], 500);
        }
    }

    /**
     * Soft delete a user account
     */
    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur supprimé avec succès.'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer cet utilisateur.'
            ], 500);
        }
    }

    /**
     * Permanently delete a user account
     */
    public function forceDeleteUser($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            $user->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur supprimé définitivement.'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer définitivement cet utilisateur.'
            ], 500);
        }
    }

    /**
     * Restore a deleted user
     */
    public function restoreUser($id)
    {
        try {
            User::withTrashed()->where('id', $id)->restore();
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur restauré avec succès.'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de restaurer cet utilisateur.'
            ], 500);
        }
    }
}
