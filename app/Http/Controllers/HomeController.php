<?php

namespace App\Http\Controllers;

use App\Models\categories;
use App\Models\configurations;
use App\Models\posts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){
        $categories = categories::all();
        $configuration= configurations::first();
        $posts = posts::where('verified_at','!=', null) -> orderByDesc('created_at') -> paginate(50);
        return view("User.index", compact( "categories","posts","configuration"));
    }

    public function index_post(Request $request){
        $id = $request->id ?? "";
        return view('User.post', compact("id"));
    }

    public function index_mes_post(){
        return view('User.list_post');
    }

    public function details_post($id){
        $post = posts::find($id);
        $other_product =  posts::where('id_categorie' ,$post->id_categorie)->inRandomOrder()->take(16)->get();
        return view('User.details', compact( 'post','other_product'));

    }

    public function user_profile($id){
        $user = User::find($id);
        return view('User.profile')->with("user",$user);
    }


    public function list_proposition($id_post)
    {
        $post = posts::where('id',$id_post)->where("id_user",Auth::id())->first();
        if($post){
            return view("User.propositions", compact("post"));
        }else{
            echo "erro";
        }
    }


    public function shop(Request $request){
        $categorie =  $request->get('categorie') ?? '';  
        $sous_categorie =  $request->get('sous_categorie') ?? '';
        return view('User.shop', compact("categorie","sous_categorie"));
    }
}
