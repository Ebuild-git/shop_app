<?php

namespace App\Http\Controllers;

use App\Models\favoris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavorisController extends Controller
{
    public function index()
    {
        return view("User.favoris");
    }

    public function remove_favoris(Request $request){
            $id_favoris = $request->get("id_favoris");
            $favoris = favoris::where("id",$id_favoris)->where("id_user",Auth::user()->id)->first();
            if($favoris){
                $favoris->delete();
                return response()->json(
                    [
                        "status" => true,
                        "message"=> "Annonce rétiré !"
                    ]
                );
            }
    }
}
