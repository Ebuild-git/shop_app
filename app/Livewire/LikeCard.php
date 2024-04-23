<?php

namespace App\Livewire;

use App\Events\UserEvent;
use App\Models\likes;
use App\Models\notifications;
use App\Models\posts;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LikeCard extends Component
{
    public $post;
    public $liked = false;

    public function mount($id)
    {
        $this->post = posts::find($id);
    }

    public function render()
    {
        $this->total = likes::where("id_post", $this->post->id)->count();
        if (Auth::check()) {
            $this->liked = likes::where("id_post", $this->post->id)
                ->where('id_user', Auth::user()->id)
                ->exists();
        }

        return view('livewire.like-card');
    }

    public function like()
    {
        if (Auth::check()) {
            if ($this->liked === true) {
                likes::where("id_post", $this->post->id)
                    ->where('id_user', Auth::user()->id)
                    ->delete();


            } else {
                likes::firstOrCreate(
                    [
                        'id_post' => $this->post->id,
                        'id_user' => Auth::user()->id
                    ]
                );
                //make notification
                event(new UserEvent($this->post->id_user));
                $notification = new notifications();
                $notification->titre = Auth::user()->username . " a aimé votre publication.";
                $notification->id_user_destination = $this->post->id_user;
                $notification->type = "like";
                $notification->destination = "user";
                $notification->url = "/post/" . $this->post->id;
                $notification->message = "@" . Auth::user()->username . " Vient d'aimé votre publication";
                $notification->save();
            }
        }
    }

}