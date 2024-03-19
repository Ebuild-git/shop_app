<?php

namespace App\Livewire\User;

use App\Events\AdminEvent;
use App\Models\categories;
use App\Models\notifications;
use Livewire\WithFileUploads;
use App\Models\posts;
use App\Models\proprietes;
use App\Models\sous_categories;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Traits\ListGouvernorat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreatePost extends Component
{
    use ListGouvernorat;
    use WithFileUploads;

    public $titre, $description, $gouvernorat, $categorie, $sous_categories, $prix, $id, $post, $old_photos, $id_sous_categorie, $etat,  $selectedCategory, $selectedSubcategory;
    public $photos = [];
    public $article_propriete = [];
    public $proprietes,$quantite;

    public function mount($id)
    {
        $this->id = $id;
    }

    public function updatedSelectedCategory($value)
    {
        $this->sous_categories = sous_categories::where("id_categorie", $value)->get();
        $cat = categories::find($value);
        $this->proprietes = $cat->proprietes;
    }



    public function render()
    {
        $post = posts::where('id', $this->id)->where("id_user", Auth::user()->id)->first();
        if ($post) {
            $this->titre = $post->titre;
            $this->description = $post->description;
            $this->gouvernorat = $post->gouvernorat;
            $this->categorie = $post->id_categorie;
            $this->prix = $post->prix;
            $this->old_photos = $post->photos;
            $this->post = $post;
        }

        $categories = categories::Orderby("order")->get(['id', 'titre']);
        return view('livewire.user.create-post')
            ->with("categories", $categories)
            ->with("list_gouvernorat", $this->get_list_gouvernorat());
    }

    //validation with multi upload image
    protected $rules = [
        'titre' => 'required|min:2',
        'description' => 'required',
        'photos.*' => 'image|max:2048|min:1',
        'gouvernorat' => 'required',
        'prix' => 'required|numeric|min:1',
        'etat' => ['required', 'in:neuf,occasion'],
        'id_sous_categorie' => 'required|integer|exists:sous_categories,id'
    ];


    public function inputChanged($value)
    {
        $this->prix = $value;
    }


    public function submit()
    {
        $this->validate(); // Vous validez les données soumises

        $jsonProprietes = json_encode($this->article_propriete);

 
        $post = posts::find($this->post->id ?? "d"); // Vous cherchez le post existant par son ID
        if (!$post) {
            $post = new posts(); // Si le post n'existe pas, vous en créez un nouveau
        }
        // Traitement des photos
        if ($this->photos) {
            $data = [];
            foreach ($this->photos as $photo) {
                $name = $photo->store('uploads/posts', 'public');
                $data[] = $name;
            }

            // Si le post existe déjà, ajoutez les nouvelles images aux images existantes
            if ($post->photos) {
                $existing_photos = $post->photos; // true pour obtenir un tableau associatif
                $data = array_merge($existing_photos, $data);
            }

            $post->photos = json_encode($data);
        }

        // Mettre à jour les autres données du post
        $post->titre = $this->titre;
        $post->description = $this->description;
        $post->gouvernorat = $this->gouvernorat;
        $post->etat = $this->etat;
        $post->proprietes =  $jsonProprietes ?? [];
        $post->id_sous_categorie = $this->id_sous_categorie;
        $post->prix = $this->prix;
        $post->id_user = Auth::user()->id; // Assumant que vous utilisez le système d'authentification de Laravel
        $post->save(); // Sauvegarder le post

        // Message de succès
        event(new AdminEvent('Un post a été créé avec succès.'));
        //enregistrer la notification
        $notification = new notifications();
        $notification->type = "new_post";
        $notification->titre = Auth::user()->name . " vient de publier un article ";
        $notification->url = "/admin/publication/" . $post->id . "/view";
        $notification->message = $post->titre;
        $notification->id_post = $post->id;
        $notification->id_user = Auth::user()->id;
        $notification->destination = "admin";
        $notification->save();

        session()->flash("success", "Le post a été créé avec succès. Vous recevrez une notification une fois la publication validée par un administrateur.");

        // Réinitialiser le formulaire
        $this->reset(['titre', 'description', 'gouvernorat', 'categorie', 'prix', 'etat', 'photos']); 
    }


    public function RemoveMe($index)
    {
        array_splice($this->photos, $index, 1);
    }


    public function update()
    {
        $this->validate();
    }


    // funntion removeOldPhoto
    public function removeOldPhoto($url_image, $id_post)
    {
        $post = posts::find($id_post);
        if ($post) {
            $photosArray = $post->photos;

            //get total of image
            $totalImage = count($photosArray);
            if ($totalImage == 1) {
                session()->flash("info", "vous devez avoir au minimum une image dans votre publication");
                return;
            }
            $key = array_search($url_image, $photosArray);
            if ($key !== false) {
                unset($photosArray[$key]);
            }
            Storage::disk('public')->delete($url_image);
            $post->photos =  json_encode($photosArray);
            $post->save();
        }
    }
}
