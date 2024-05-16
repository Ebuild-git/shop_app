<?php

namespace App\Http\Controllers;

use App\Models\favoris;
use App\Models\posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavorisController extends Controller
{
    public function index()
    {
        return view("User.favoris");
    }

    public function remove_favoris(Request $request)
    {
        $id_favoris = $request->get("id_favoris");
        $favoris = favoris::where("id", $id_favoris)->where("id_user", Auth::user()->id)->first();
        if ($favoris) {
            $favoris->delete();
            return response()->json(
                [
                    "status" => true,
                    "message" => "Annonce rétiré !"
                ]
            );
        }
    }


    public function ajouter_favoris(Request $request)
    {
        $id_post = $request->get("id_post");
        $post = posts::find($id_post);
        if ($post) {
            //verifier si il n'a pas deja ajouter a ses favoris
            $favoris = favoris::where("id_post", $id_post)
                ->where("id_user", Auth::user()->id)
                ->first();
            if (!$favoris) {
                $favoris = new favoris();
                $favoris->id_post = $id_post;
                $favoris->id_user = Auth::user()->id;
                if ($favoris->save()) {
                    return response()->json(
                        [
                            "status" => true,
                            "action" => "ajouté",
                            "message" => "Annonce Ajouté aux favoris !"
                        ]
                    );
                }
            } else {
                $favoris->delete();
                return response()->json(
                    [
                        "status" => true,
                        "action" => "retiré",
                        "message" => "Annonce retiré des favoris !"
                    ]
                );
            }
        } else {
            return response()->json(
                [
                    "status" => false,
                    "action" => false,
                    "message" => "Annonce introuvable !"
                ]
            );
        }
    }
}
