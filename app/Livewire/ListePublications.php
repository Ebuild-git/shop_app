<?php

namespace App\Livewire;

use App\Events\UserEvent;
use App\Models\categories;
use App\Models\notifications;
use App\Models\posts;
use App\Models\regions;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class ListePublications extends Component
{
    use WithPagination;

    public $type, $categories, $mot_key, $categorie_key, $region_key, $deleted;

    public function mount($deleted)
    {
        $this->deleted = $deleted;
    }

    public function render()
    {
        $this->categories = categories::all();


        $postsQuery = posts::Orderby("id", "Desc")->select("id", "titre", "photos", "id_user","deleted_at", "created_at", "id_sous_categorie","old_prix", "statut", "prix", "id_region");

        if($this->deleted == 'oui'){
            $publications = $postsQuery->onlyTrashed();
        }
        if (strlen($this->type) > 0) {
            $postsQuery->where('statut',  $this->type);
        }

        // Filtrage par mot-clé
        if (strlen($this->mot_key) > 0) {
            $postsQuery->where('titre', 'like', '%' . $this->mot_key . "%")
                ->OrWhere('description', 'like', '%' . $this->mot_key . "%");
        }

        // Filtrage par catégories
        if (strlen($this->categorie_key) > 0) {
            $categorie = categories::find($this->categorie_key);
            if ($categorie) {
                $postsQuery->whereHas('sous_categorie_info', function ($query) use ($categorie) {
                    $query->where('id_categorie', $categorie->id);
                });
            }
        }
        

        // Filtrage par region
        if (strlen($this->region_key) > 0) {
            $postsQuery->where('id_region', $this->region_key);
        }


        $posts = $postsQuery->paginate(50);
        $regions = regions::all(['id', 'nom']);

        //count total trashed post
        $trashCount = posts::onlyTrashed()->count();
        return view('livewire.liste-publications', compact('posts', 'regions', 'trashCount'));
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
            $notification->url = "/post/" . $post->id;
            $notification->message = "Nous vous informons que votre publication  " . $post->titre . " a été retourné a la vente !";
            $notification->save();
            event(new UserEvent($post->id_user));


            session()->flash("success", "Le publication a été validée");
        } else {
            session()->flash("error", "Une erreur est survenue lors de la validation de la publication, veuillez réessayer plus tard.");
        }
    }


    public function filtre()
    {
        $this->resetPage();
    }



    public function delete($id)
    {
        $post = posts::find($id);
        if ($post) {
            //update verified_at date
            $post->delete();
            session()->flash("success", "La publication a été supprimé !");
        } else {
            session()->flash("error", "Une erreur est survenue lors de la suppression");
        }
    }

    public function restore($id){
        $post=posts::withTrashed()->where('id',$id)->first();
        if ($post) {
            $post->update(['motif_suppression'=>null]);
            $post->restore();
            session()->flash("success","La publication à été restaurer!");
        } else {
            session()->flash("error","Cette publication n'existe pas.");
        }
    }

    public function delete_definitivement($id){
        $post=posts::withTrashed()->findOrFail($id);
        foreach ($post->photos as $img) {
            Storage::disk('public')->delete($img);
        }
        $post->forceDelete();
        session()->flash("success","La publication a été définitivement supprimée !");
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
