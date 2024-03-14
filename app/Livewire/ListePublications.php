<?php

namespace App\Livewire;

use App\Events\UserEvent;
use App\Models\categories;
use App\Models\notifications;
use App\Models\posts;
use Livewire\Component;
use Livewire\WithPagination;

class ListePublications extends Component
{
    use WithPagination;

    public $type, $categories, $mot_key, $categorie_key, $gouvernorat_key;
    public $gouvernorats = [];

    public function mount($type, $gouvernorats)
    {
        $this->type = $type;
        $this->gouvernorats = $gouvernorats;
    }


    public function render()
    {
        $this->categories = categories::all();


        $postsQuery = posts::Orderby("id", "Desc");

        if ($this->type == "attente") {
            $postsQuery->whereNull("verified_at");
        } elseif ($this->type == "publiés") {
            $postsQuery->whereNotNull("verified_at")
                ->whereNull("sell_at");
        } elseif ($this->type == "vendu") {
            $postsQuery->whereNotNull("sell_at");
        }

        // Filtrage par mot-clé
        if (strlen($this->mot_key) > 0) {
            $postsQuery->where('titre', 'like', '%' . $this->mot_key . "%")
                ->OrWhere('description', 'like', '%' . $this->mot_key . "%");
        }

        // Filtrage par catégories
        if (strlen($this->categorie_key) > 0) {
            $postsQuery->where('id_categorie', $this->categorie_key);
        }

        // Filtrage par gouvernorat
        if (strlen($this->gouvernorat_key) > 0) {
            $postsQuery->where('gouvernorat', $this->gouvernorat_key);
        }


        $posts = $postsQuery->paginate(50);

        return view('livewire.liste-publications', compact('posts'));
    }

    public function valider($id)
    {
        //valider le post
        $post = posts::find($id);
        if ($post) {
            //update verified_at date
            $post->verified_at = now();
            $post->statut = 'vente';
            $post->save();

             //make notification
             $notification = new notifications();
             $notification->titre = "Une vente a été retouner ";
             $notification->id_user_destination  =  $post->id_user;
             $notification->type = "alerte";
             $notification->url = "/post/".$post->id;
             $notification->message = "Nous vous informons que votre publication  " . $post->titre . " a été retourné a la vente !";
             $notification->save();
             event(new UserEvent($post->id_user));

             
            session()->flash("success", "Le publication a été validée");
        } else {
            session()->flash("error", "Une erreur est survenue lors de la validation de la publication, veuillez réessayer plus tard.");
        }
    }


    public function filtre(){
        $this->resetPage();
    }



    public function delete($id)
    {
        $post = posts::find($id);
        if ($post) {
            //update verified_at date
            $post->delete();
            session()->flash("success", "La publication a été supprimé définitivement");
        } else {
            session()->flash("error", "Une erreur est survenue lors de la suppression");
        }
    }



    public function vendu($id)
    {
        //valider le post
        $post = posts::find($id);
        if ($post) {
            //update verified_at date
            $post->sell_at = now();
            $post->save();
            session()->flash("success", "Le publication a été vendu");
        } else {
            session()->flash("error", "Une erreur est survenue lors de la validation de la publication, veuillez réessayer plus tard.");
        }
    }
}
