<?php

namespace App\Livewire\User;

use App\Models\signalements;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Events\AdminEvent;
use App\Models\notifications;

class Signalement extends Component
{

    public $post, $type, $message;
    public $is_send = false;
    public function mount($post)
    {
        $this->post = $post;
    }

    protected $rules = [
        'type' => 'required|string',
        'message' => 'required|min:15'
    ];

    public function render()
    {
        return view('livewire.user.signalement');
    }

    public function signaler()
    {
        $this->validate();

        if (signalements::where('id_user_make', Auth::user()->id)
            ->where('id_post', $this->post->id)->exists()
        ) {
            session()->flash('error', 'Vous avez déjà signalé cette publication');
            return;
        }


        $signalement = new signalements();
        $signalement->id_post = $this->post->id;
        $signalement->id_user_make = Auth::user()->id;
        $signalement->message = $this->message;
        $signalement->type = $this->type;
        $signalement->save();
        session()->flash("success", "Votre signalement a été enregistré");
        $this->is_send = true;

        event(new AdminEvent('Un post a été signalé.'));

        $notification = new notifications();
        $notification->type = "new_post";
        $notification->titre = Auth::user()->username . " a signalé une publication";
        $notification->url = "/admin/publication/" . $this->post->id . "/view";
        $notification->message = "Raison : " . $this->type . " — " . $this->message;
        $notification->id_post = $this->post->id;
        $notification->id_user = Auth::user()->id;
        $notification->destination = "admin";
        $notification->save();

        //reset form
        $this->reset(['type','message']);
        $this->dispatch('report-submitted');
    }
}
