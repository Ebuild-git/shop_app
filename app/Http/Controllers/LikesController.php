<?php

namespace App\Http\Controllers;

use App\Models\likes;
use App\Models\posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikesController extends Controller
{
    public function index()
    {
        return view("User.likes");
    }



    public function remove_liked(Request $request)
    {
        $id_like = $request->get("id_like");
        $like = likes::where("id", $id_like)->where("id_user", Auth::user()->id)->first();
        if ($like) {
            $like->delete();
            $count = likes::where("id_user", Auth::user()->id)->count();
            return response()->json(
                [
                    "status" => true,
                    "message" => "Annonce rétiré !",
                    "count" => $count
                ]
            );
        }
    }


    public function like_post(Request $request)
    {
        $id_post = $request->get("id_post");
        $user = Auth::user();

        //verification de l'existance du post
        $post = posts::find($id_post);
        // if (!$post) {
        //     return response()->json(
        //         [
        //             "status" => false,
        //             "message" => "Annonce rétiré !"
        //         ]
        //     );
        // }

        //verifier que celui qui like nest pas le proprietaie du post
        if ($post->id_user == $user->id) {
            return response()->json(
                [
                    "status" => false,
                    "message" => "Vous ne pouvez pas liker votre propre annonce !"
                ]
            );
        }

        //verifier que l'utilisateur n'a pas deja liker l'annonce
        $like = likes::where("id_user", $user->id)->where("id_post", $id_post)->first();
        if ($like) {
            $like->delete();
            return response()->json(
                [
                    "status" => true,
                    "action" => "retiré",
                    "message" => __("message.ad_disliked"),
                    "count" => $post->getLike->count(),
                ]
            );
        } else {
            $like = new likes();
            $like->id_post = $post->id;
            $like->id_user = $user->id;
            $like->save();
            return response()->json(
                [
                    "status" => true,
                    "message" => __("message.ad_liked"),
                    "action" => "ajouté",
                    "count" => $post->getLike->count(),
                ]
            );
        }


    }
}
