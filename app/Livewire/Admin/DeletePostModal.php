<?php

namespace App\Livewire\Admin;

use App\Events\UserEvent;
use App\Models\notifications;
use App\Models\posts;
use Livewire\Component;

class DeletePostModal extends Component
{

    public $motif, $post, $titre;

    protected $listeners = ['sendDataPostForDelete'];

    public function sendDataPostForDelete($id_post)
    {
        $post = posts::where('id', $id_post)->first();
        $this->post = $post;
        $this->titre = $post->titre;
    }



    public function render()
    {
        return view('livewire.admin.delete-post-modal');
    }


    function delete()
    {
        if ($this->post) {
            $post = $this->post;
            $post->update(['motif_suppression' => $this->motif]);

            //generer une notification
            $notification = new notifications();
            $notification->titre = "Cher(e) " . $post->user_info->username;
            $notification->id_user_destination  =  $post->id_user;
            $notification->type = "alerte";
            $notification->url = "#";
            $notification->message = "Votre annonce pour  " . $post->titre . " aa été retirée par l'équipe de SHOPIN La raison de la suppression est la suivante: <b>" . $this->motif . "</b> <br/> Merci de votre compréhension. ";
            $notification->save();
            event(new UserEvent($post->id_user));

            $post->delete();
            //dispatch event
            $this->dispatch('annonce-delete');

            $this->dispatch('alert', ['message' => "Annonce supprimé !", 'type' => 'success']);
        }
    }
}
