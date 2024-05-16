<?php

namespace App\Http\Controllers;

use App\Models\likes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikesController extends Controller
{
    public function index(){
        return view("User.likes");
    }



    public function remove_liked(Request $request){
        $id_like = $request->get("id_like");
        $like = likes::where("id",$id_like)->where("id_user",Auth::user()->id)->first();
        if($like){
            $like->delete();
            return response()->json(
                [
                    "status" => true,
                    "message"=> "Annonce rétiré !"
                ]
            );
        }

    }
}
