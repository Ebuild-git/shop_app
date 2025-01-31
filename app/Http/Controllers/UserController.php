<?php

namespace App\Http\Controllers;

use App\Models\posts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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

    // public function details_user(Request $request)
    // {
    //     $id = $request->id;
    //     try {
    //         $user = User::findOrFail($id);
    //         $posts = posts::where('id_user', $user->id)->paginate(30);
    //         return view("Admin.clients.profile")
    //             ->with("user", $user)
    //             ->with("posts", $posts);
    //     } catch (\Throwable $th) {

    //         abort(404, "Page non trouvée");
    //     }
    // }
    public function details_user(Request $request)
    {
        $id = $request->id;
        try {
            $user = User::findOrFail($id);
            $posts = Posts::where('id_user', $user->id)->paginate(30);

            // Get the current CIN image
            $currentCinImg = $user->cin_img ? asset('storage/' . $user->cin_img) : null;

            // Get all old CIN images from JSON field
            $oldCinImages = json_decode($user->old_cin_images, true) ?? [];
            $oldCinImages = array_map(fn($img) => asset('storage/' . $img), $oldCinImages);

            return view("Admin.clients.profile")
                ->with("user", $user)
                ->with("posts", $posts)
                ->with("currentCinImg", $currentCinImg)
                ->with("oldCinImages", $oldCinImages);
        } catch (\Throwable $th) {
            abort(404, "Page non trouvée");
        }
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
