<?php

namespace App\Livewire;

use App\Models\notifications;
use App\Models\posts;
use App\Models\propositions;
use Livewire\Component;

class DetailsPublicationAction extends Component
{
    public $post, $id, $verified_at;
    public function mount($id)
    {
        $this->id = $id;
    }
    public function render()
    {
        $this->post = posts::find($this->id);
        return view('livewire.details-publication-action');
    }

    public function valider()
    {
        //valider le post
        $post = posts::find($this->post->id);
        if ($post) {
            //update verified_at date
            $post->verified_at = now();
            $post->save();
            session()->flash("success", "Le publication a été validée");

            //make notification
            $notification = new notifications();
            $notification->titre = "Votre publication a été validé !";
            $notification->id_user_destination  = $post->id_user;
            $notification->type = "alerte";
            $notification->message = "Nous vous informons que votre publication  $post->titre a été validé par les administrateurs";
            $notification->save();
        } else {
            session()->flash("error", "Une erreur est survenue lors de la validation de la publication, veuillez réessayer plus tard.");
        }
    }





    public function remettre()
    {
        $post = posts::find($this->post->id);
        if ($post) {
            //update post
            $post->update(
                [
                    'sell_at' => null,
                    'id_user_buy' => null,
                ]
            );

            //reinitialiser les propositions
            $proposition = propositions::where("id_post", $post->id)->where("etat", 'accepté')->first();
            if ($proposition) {
                $proposition->update(
                    [
                        'etat' => "traitement"
                    ]
                );
            }

            //envoie de la notification a celui qui a poster pour l'informer
            //make notification
            $notification = new notifications();
            $notification->titre = "Une vente a été retouner ";
            $notification->id_user_destination  =  $post->id_user;
            $notification->type = "alerte";
            $notification->message = "Nous vous informons que votre publication  " . $post->titre . " a été retourné a la vente !";
            $notification->save();

            //show success message
            session()->flash('success', 'La publication à bien été remise');
        } else {
            //show error message
            session()->flash("error", "Une erreur est survenue, veuillez réessayer plus tard.");
        }
    }





    public function delete()
    {
        $post = posts::find($this->post->id);
        if ($post) {
            //update verified_at date
            $post->delete();
            return redirect()->back();
        } else {
            session()->flash("error", "Une erreur est survenue lors de la suppression");
        }
    }
}
