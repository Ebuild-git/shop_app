<?php

namespace App\Http\Controllers;

use App\Models\favoris;
use App\Models\posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavorisController extends Controller
{
    public function index(Request $request)
    {

        $month = $request->input('month') ?? null;
        $year = $request->input('year') ?? null;
        $favoris = favoris::where('id_user',Auth::id())
        ->orderBy('created_at', 'desc');
        if ($month && $year) {
            $favoris->whereYear('created_at', $year)
                  ->whereMonth('created_at', $month);
        }
        $favoris = $favoris->paginate(10);
        return view("User.favoris")
        ->with("favoris", $favoris)
        ->with("year", $year)
        ->with("month", $month);
    }

    public function remove_favoris(Request $request)
    {
        $id_favoris = $request->get("id_favoris");
        $favoris = favoris::where("id", $id_favoris)->where("id_user", Auth::user()->id)->first();
        if ($favoris) {
            $favoris->delete();
            $count = favoris::where("id_user", Auth::user()->id)->count();
            return response()->json(
                [
                    "status" => true,
                    "message" => __("favorite_removed"),
                    "count" => $count,
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
                            "message" => __("favorite_added")
                        ]
                    );
                }
            } else {
                $favoris->delete();
                return response()->json(
                    [
                        "status" => true,
                        "action" => "retiré",
                        "message" => __("favorite_removed")
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
