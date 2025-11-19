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
        } else if ($request->type != "all"  && $request->type != "shop") {
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
            $oldCinImages = json_decode($user->old_cin_images, true) ?? [];
            $oldCinImages = array_map(fn($img) => asset('storage/' . $img), $oldCinImages);

            return view("Admin.clients.profile")
                ->with("user", $user)
                ->with("posts", $posts)
                ->with("decryptedRib", $decryptedRib)
                ->with("currentCinImg", $currentCinImg)
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
        $notification->id_user_destination  = $user->id;
        $notification->type = "alerte";
        $notification->url = "/informations";
        $notification->destination = "user";
        $notification->id_user = $user->id;
        $notification->message = "Nous vous informons que votre photo de profile a été validé par les administrateurs.";
        $notification->save();
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
}
