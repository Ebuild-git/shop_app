<?php

namespace App\Livewire;

use App\Events\UserEvent;
use App\Models\notifications;
use App\Models\posts;
use App\Models\propositions;
use App\Models\ratings;
use Livewire\Component;
use Illuminate\Support\Facades\Crypt;

class DetailsPublicationAction extends Component
{
    public $post, $id, $verified_at;
    public $motif_suppression;
    public $rib_decrypted;


    public function mount($id)
    {
        $this->id = $id;
    }
    public function render()
    {
        $this->post = posts::withTrashed()->find($this->id);
        $this->rib_decrypted = null;
        try {
            if ($this->post?->acheteur?->rib_number) {
                $this->rib_decrypted = Crypt::decryptString($this->post->acheteur->rib_number);
            }
        } catch (\Exception $e) {
            $this->rib_decrypted = null;
        }
        return view('livewire.details-publication-action');
    }

    public function valider()
    {
        //valider le post
        $post = posts::find($this->post->id);
        if ($post) {
            //update verified_at date
            $post->verified_at = now();
            $post->statut = 'vente';
            $post->save();

            event(new UserEvent($post->id_user));
            //make notification
            $notification = new notifications();
            $notification->titre = "Votre publication a été validé !";
            $notification->id_user_destination  = $post->id_user;
            $notification->type = "alerte";
            $notification->url = "/post/".$post->id;
            $notification->id_post = $post->id;
            $notification->destination = "user";
            $notification->id_user = $post->id_user;
            $notification->message = "Nous vous informons que votre publication
            <a href='" . route('details_post2', ['id' => $post->id, 'titre' => $post->titre]) . "' class='underlined-link'>
                $post->titre
            </a> a été validé par les administrateurs.";
            $notification->save();


            // Message de succès
            session()->flash("success", "Le publication a été validée");

        } else {
            session()->flash("error", "Une erreur est survenue lors de la validation de la publication, veuillez réessayer plus tard.");
        }
    }


    public function remettre()
    {
    $post = posts::find($this->post->id);
    if ($post) {
        $post->update([
            'sell_at' => null,
            'id_user_buy' => null,
            'statut' => 'vente',
        ]);

        event(new UserEvent($post->id_user));

        $notification = new notifications();
        $notification->titre = "Une vente a été retournée";
        $notification->id_user_destination = $post->id_user;
        $notification->type = "alerte";
        $notification->url = "/post/" . $post->id;
        $notification->message = "Nous vous informons que votre publication \"" . $post->titre . "\" a été retournée à la vente !";
        $notification->save();
        session()->flash('success', 'La publication a bien été remise');
        } else {
            // Show error message
            session()->flash("error", "Une erreur est survenue, veuillez réessayer plus tard.");
        }
    }

    public function refuser(){

    }

    public function mark_as_livrer(){
        $post = posts::find($this->post->id);
        if ($post) {
            //update post
            $post->statut = "livré";
            $post->update();

            //ouvrir la possibilite de noter en creer un enregistrement rating
            $rating = new ratings();
            $rating->id_user_sell = $post->id_user;
            $rating->id_user_buy = $post->id_user_buy;
            $rating->id_post = $post->id;
            $rating->date_buy = now();
            $rating->save();

            //creer la notification pour informer l'acheteru quil peut desormais noter ce vendeur

            //flash message
            session()->flash('success', 'La publication à bien été livré');
        }
    }

    public function confirmDelete($id)
    {
        $post = posts::find($id);

        if ($post) {
            if (!empty($this->motif_suppression)) {
                $post->motif_suppression = $this->motif_suppression;
                $post->save();

                $greeting = $post->user_info->gender === 'female' ? "Chère" : "Cher";
                $notification = new notifications();
                $notification->titre = "{$greeting} " . $post->user_info->username;
                $notification->id_user_destination = $post->id_user;
                $notification->type = "alerte";
                $notification->url = "#";
                $notification->message = "
                    Votre annonce pour <strong>" . htmlspecialchars($post->titre) . "</strong> a été retirée par l'équipe de <span style='color: black; font-weight: 500;'>SHOP</span><span style='color: #008080; font-weight: 500;'>IN</span>.
                    La raison de la suppression est la suivante: <b style='color: #e74c3c;'>" . htmlspecialchars($this->motif_suppression) . "</b> <br/>
                    Merci pour votre compréhension.
                ";
                $notification->save();
                event(new UserEvent($post->id_user));

                $post->delete();
                session()->flash('success', 'La publication a été supprimée avec succès !');
                $this->dispatch('hide-delete-modal');

            } else {
                session()->flash('error', 'Veuillez sélectionner un motif de suppression.');
                $this->dispatch('hide-delete-modal');

            }
        } else {
            session()->flash('error', "Une erreur est survenue lors de la suppression !");
            $this->dispatch('hide-delete-modal');
        }
    }

}
