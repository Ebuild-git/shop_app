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
        // $date = $request->get('date') ?? null;
        // $favoris = favoris::where('id_user',Auth::id());
        // if($date){
        //     $favoris->whereYear('Created_at', date('Y', strtotime($date)))
        //     ->whereMonth('Created_at', date('m', strtotime($date)));
        // }
        // $favoris = $favoris->paginate(10);
        // return view("User.favoris")
        // ->with("favoris", $favoris)
        // ->with("date", $date);
        $month = $request->input('month') ?? null;
        $year = $request->input('year') ?? null;
        $favoris = favoris::where('id_user',Auth::id());
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
                    "message" => "Article retiré de mes favoris !",
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
                            "message" => "Article ajouté aux favoris !"
                        ]
                    );
                }
            } else {
                $favoris->delete();
                return response()->json(
                    [
                        "status" => true,
                        "action" => "retiré",
                        "message" => "Article retiré des favoris !"
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
