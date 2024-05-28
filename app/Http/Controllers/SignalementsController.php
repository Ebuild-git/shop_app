<?php

namespace App\Http\Controllers;

use App\Models\posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\Colors\Rgb\Channels\Red;

class SignalementsController extends Controller
{



    public function liste_publications_signaler(Request $request)
    {
        $date = $request->input('date');
        $query = posts::withCount('signalements')->has('signalements');
        if ($date) {
            $query->whereDate('created_at', $date);
        }
        $posts = $query->paginate(50);
        return view('Admin.publications.signalements')
        ->with("date",$date)
        ->with('posts', $posts);
    }
    




    public function liste_signalement_publications($id_post)
    {
        $post = posts::find($id_post);
        if ($post) {
            return view("Admin.publications.liste-signalement")->with("post", $post);
        } else {
            return redirect()->route('post_signalers');
        }
    }


    public function delete($id)
    {
        $post = posts::find($id);
        if ($post) {
            $post->signalements()->delete();
            $post->delete();
        }
        
        return redirect()->back()->with("success", "L'annonce a été supprimé");
    }
}
