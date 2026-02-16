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
            $notification->id_user_destination = $post->id_user;
            $notification->type = "alerte";
            $notification->url = "#";
            $notification->message = "Votre annonce pour  " . $post->titre . " a été retirée par l'équipe de SHOPIN La raison de la suppression est la suivante: <b>" . $this->motif . "</b> <br/> Merci pour votre compréhension. ";
            $notification->save();
            event(new UserEvent($post->id_user));

            // Send FCM notification
            $fcmService = app(\App\Services\FcmService::class);
            $sent = $fcmService->sendToUser(
                $post->id_user,
                "Cher(e) " . $post->user_info->username,
                "Votre annonce pour " . $post->titre . " a été retirée. Raison: " . $this->motif,
                [
                    'type' => 'alerte',
                    'notification_id' => $notification->id,
                    'destination' => 'user',
                    'action' => 'post_deleted',
                    'post_id' => $post->id,
                ]
            );

            if ($sent) {
                \Log::info("FCM notification sent successfully", [
                    'user_id' => $post->id_user,
                    'notification_id' => $notification->id,
                    'type' => 'post_deleted'
                ]);
            } else {
                \Log::warning("FCM notification failed to send", [
                    'user_id' => $post->id_user,
                    'notification_id' => $notification->id,
                    'reason' => 'User has no FCM token or token invalid'
                ]);
            }

            $post->delete();
            //dispatch event
            $this->dispatch('annonce-delete');

            $this->dispatch('alert', ['message' => "Annonce supprimé !", 'type' => 'success']);
        }
    }
}
