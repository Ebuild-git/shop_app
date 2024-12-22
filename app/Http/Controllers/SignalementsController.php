<?php

namespace App\Http\Controllers;

use App\Models\posts;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\Colors\Rgb\Channels\Red;
use App\Models\notifications;
use App\Events\UserEvent;

class SignalementsController extends Controller
{



    public function liste_publications_signaler(Request $request)
    {
        $date = $request->input('date');
        $keyword = $request->input('keyword');
        $query = posts::with(['signalements.auteur'])
        ->withCount('signalements')
        ->has('signalements')
        ->join('signalements', 'signalements.id_post', '=', 'posts.id')
        ->select('posts.*')
        ->orderBy('signalements.created_at', 'desc');

        if ($date) {
            $query->whereDate('created_at', $date);
        }

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('id', 'like', '%' . $keyword . '%')
                  ->orWhere('titre', 'like', '%' . $keyword . '%')
                  ->orWhereHas('user_info', function ($query) use ($keyword) {
                      $query->where('username', 'like', '%' . $keyword . '%');
                  })
                  ->orWhereHas('signalements', function ($query) use ($keyword) {
                      $query->whereHas('auteur', function ($q) use ($keyword) {
                          $q->where('username', 'like', '%' . $keyword . '%');
                      });
                  });
            });
        }
        $posts = $query->paginate(50);
        return view('Admin.publications.signalements')
        ->with("date",$date)
        ->with("keyword", $keyword)
        ->with('posts', $posts);
    }

    public function liste_signalement_publications($id_post)
    {
        $post = posts::withTrashed()->find($id_post);
        if ($post) {
            return view("Admin.publications.liste-signalement")->with("post", $post);
        } else {
            return redirect()->route('post_signalers');
        }
    }

    public function liste_signalement_by_user($user_id)
    {
        $user = User::find($user_id);
        if ($user) {
            return view("Admin.publications.user-signalement")->with("user", $user);
        } else {
            return redirect()->route('post_signalers');
        }

    }





    public function delete($id, Request $request)
    {

        $post = posts::find($id);

        if ($post) {
            $motif_suppression = $request->input('motif_suppression');
            $post->motif_suppression = $motif_suppression;
            $post->save();

            $greeting = $post->user_info->gender === 'female' ? "Chère" : "Cher";

            // Create a notification with styled content
            $notification = new Notifications();
            $notification->titre = "{$greeting} " . $post->user_info->username;
            $notification->id_user_destination = $post->id_user;
            $notification->type = "alerte";
            $notification->url = "#";
            $notification->message = "
                Votre annonce pour <strong>" . htmlspecialchars($post->titre) . "</strong> a été retirée par l'équipe de <span style='color: black; font-weight: 500;'>SHOP</span><span style='color: #008080; font-weight: 500;'>IN</span>.
                La raison de la suppression est la suivante: <b style='color: #e74c3c;'>" . htmlspecialchars($motif_suppression) . "</b> <br/>
                Merci pour votre compréhension.
            ";
            $notification->save();

            // Optionally dispatch any events here (if needed)
            event(new UserEvent($post->id_user));

            $post->delete();

            // Optionally return a response
            return redirect()->back()->with("success", "La publication a été supprimée avec le motif: {$motif_suppression} !");
        } else {
            return redirect()->back()->with("error", "Une erreur est survenue lors de la suppression !");

        }
    }
}
