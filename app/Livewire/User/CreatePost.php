<?php

namespace App\Livewire\User;

use App\Models\categories;
use Livewire\WithFileUploads;
use App\Models\posts;
use App\Models\sous_categories;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Traits\ListGouvernorat;
use Illuminate\Support\Facades\Storage;

class CreatePost extends Component
{
    use ListGouvernorat;
    use WithFileUploads;

    public $titre, $description, $photos, $ville, $gouvernorat, $categorie, $prix, $id, $post, $old_photos,$id_sous_categorie;


    public function mount($id)
    {
        $this->id = $id;
    }


    public function render()
    {
        $post = posts::where('id', $this->id)->where("id_user", Auth::user()->id)->first();
        if ($post) {
            $this->titre = $post->titre;
            $this->description = $post->description;
            $this->ville = $post->ville;
            $this->gouvernorat = $post->gouvernorat;
            $this->categorie = $post->id_categorie;
            $this->prix = $post->prix;
            $this->old_photos = json_decode($post->photos);
            $this->post = $post;
        }

        $categories = categories::all(['id', 'titre']);
        $sous_categories = sous_categories::all(["id", "titre", "id_categorie"]);
        return view('livewire.user.create-post')
            ->with("categories", $categories)
            ->with("sous_categories", $sous_categories)
            ->with("list_gouvernorat", $this->get_list_gouvernorat());
    }

    //validation with multi upload image
    protected $rules = [
        'titre' => 'required|min:6',
        'description' => 'required',
        'photos.*' => 'image|max:2048|min:1',
        'ville' => 'required',
        'categorie' => 'required|integer|exists:categories,id',
        'gouvernorat' => 'required',
        'prix' => 'required|numeric|min:1',
        'id_sous_categorie' => 'required|integer|exists:sous_categories,id'
    ];



    public function submit()
    {
        $this->validate(); // Vous validez les données soumises

        //verifions que la sous categorie appartient bien a la categorie mere
        $sous_cat = sous_categories::find($this->id_sous_categorie);
        if(!$sous_cat){
            session()->flash('error', __('Sous catégorie inexistante'));
            return;
        }
        if($sous_cat->id_categorie != $this->categorie){
            session()->flash('error', __('La sous catégorie ne correspond pas à la catégorie choisie'));
            return;
        }

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
                $existing_photos = json_decode($post->photos, true); // true pour obtenir un tableau associatif
                $data = array_merge($existing_photos, $data);
            }

            $post->photos = json_encode($data);
        }

        // Mettre à jour les autres données du post
        $post->titre = $this->titre;
        $post->description = $this->description;
        $post->ville = $this->ville;
        $post->gouvernorat = $this->gouvernorat;
        $post->id_categorie = $this->categorie;
        $post->id_sous_categorie = $this->id_sous_categorie;
        $post->prix = $this->prix;
        $post->id_user = Auth::user()->id; // Assumant que vous utilisez le système d'authentification de Laravel
        $post->save(); // Sauvegarder le post

        // Message de succès
        session()->flash("success", "Le post a été créé avec succès. Vous recevrez une notification une fois la publication validée par un administrateur.");

        // Réinitialiser le formulaire
        $this->reset(['titre', 'description', 'ville', 'gouvernorat', 'categorie', 'prix']);
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
            $photosArray = json_decode($post->photos, true);

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
