<?php

namespace App\Livewire\User;

use App\Events\UserEvent;
use App\Models\notifications;
use App\Models\propositions;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ListePropositions extends Component
{
    public $post, $propositions;

    public function mount($post)
    {
        $this->post = $post;
    }

    public function render()
    {
        $this->propositions = $this->post->propositions;

        return view('livewire.user.liste-propositions');
    }


    public function supprimer($id_acheteur)
    {
        $proposition = propositions::where("id_acheteur", $id_acheteur)
            ->where("id_post", $this->post->id)
            ->where("id_vendeur", Auth::id())
            ->first();
        if ($proposition) {
            $proposition->etat = "refusé";
            $proposition->save();

            //show success message
            session()->flash('error', 'La proposition a été refusée avec succès !');
        }
    }

    public function accepter($id_acheteur)
    {
        $proposition = propositions::where("id_post", $this->post->id)
            ->where("id_acheteur", $id_acheteur)->first();
        if ($proposition) {
            $proposition->etat = 'accepté';
            if ($proposition->save()) {
                $this->post->update(
                    [
                        'sell_at' => Now(),
                        'id_user_buy' => $id_acheteur
                    ]
                );

                //make notification
                $notification = new notifications();
                $notification->titre = "Félicitation pour votre commande !";
                $notification->id_user_destination  =  $id_acheteur;
                $notification->type = "user";
                $notification->url ="/post/".$this->post->id;
                $notification->message = "Nous vous informons que votre commande chez  " . $this->post->user_info->name . " a été acceptée !";
                $notification->save();
                event(new UserEvent($this->post->id_user));

                //show success message
                session()->flash('success', 'La proposition a été acceptée avec succès!');
            } else {
                //show error message
                session()->flash('error', 'Une erreur est survenue, veuillez réessayer plus tard!');
            }
        } else {
            //show error message
            session()->flash('error', 'Une erreur est survenue, veuillez réessayer plus tard!');
        }
    }

    public function retaurer()
    {
        $proposition = propositions::where("id_post", $this->post->id)
            ->where("id_vendeur", Auth::id())->get();
        if ($proposition) {
            foreach ($proposition as $p) {
                $p->etat = "traitement";
                $p->save();
            }
            //show success message
            session()->flash('success', 'Toutes les propositions ont bien été retournées en traitement.');
        } else {
            //show error message
            session()->flash('error', 'Aucune proposition n\'a été trouvée pour cette annonce.');
        }
    }
}
