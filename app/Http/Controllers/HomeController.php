<?php

namespace App\Http\Controllers;

use App\Models\categories;
use App\Models\posts;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $categories = categories::all();
        $posts = posts::where('verified_at','!=', null) -> orderByDesc('created_at') -> paginate(50);
        return view("User.index", compact( "categories","posts"));
    }

    public function index_post(){
        return view('User.post');
    }

    public function index_mes_post(){
        return view('User.list_post');
    }

    public function details_post($id){
        $post = posts::find($id);
        $other_product =  posts::where('id_categorie' ,$post->id_categorie)->inRandomOrder()->take(16)->get();
        return view('User.details', compact( 'post','other_product'));

    }
}
