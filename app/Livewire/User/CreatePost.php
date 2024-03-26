<?php

namespace App\Livewire\User;

use App\Events\AdminEvent;
use App\Models\categories;
use App\Models\notifications;
use Livewire\WithFileUploads;
use App\Models\posts;
use App\Models\proprietes;
use App\Models\regions;
use App\Models\regions_categories;
use App\Models\sous_categories;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreatePost extends Component
{
    use WithFileUploads;

    public $titre, $description, $region, $categorie, $sous_categories, $prix, $id,$prix_achat, $post, $old_photos, $id_sous_categorie, $etat,  $selectedCategory, $selectedSubcategory;
    public $photos = [];
    public $article_propriete = [];
    public $proprietes, $quantite;
    public $extimation_prix =0  ;

    public function mount($id)
    {
        $this->id = $id;
    }

    public function updatedSelectedCategory($value)
    {
        if($value != "x"){
            $c =sous_categories::where("id_categorie", $value)->get();
            $this->sous_categories  = $c;
            $cat = categories::find($value);
            $this->proprietes = $cat->proprietes;
    
            if (!is_null($value) && !is_null($this->region) && !is_null($this->prix)) {
                $this->calcule_estimation($this->region, $value, $this->prix);
            }
        }else{
            $this->selectedCategory = null;
        }
       
    }

    public function updatedRegion($value)
    {
        $this->region =  $value;
        if (!is_null($value) && !is_null($this->selectedCategory) && !is_null($this->prix)) {
            $this->calcule_estimation($value, $this->selectedCategory, $this->prix);
        }
    }

    public function updatedPrix($value)
    {
        $this->prix =  $value;
        if (!is_null($value) && !is_null($this->selectedCategory) && !is_null($this->prix)) {
            $this->calcule_estimation($this->region, $this->selectedCategory, $value);
        }
    }


    public function updatedPtitre($value){
        $this->titre = $value;
    }



    public function calcule_estimation($id_region, $id_categorie, $prix)
    {
        $regions_categorie = regions_categories::where('id_region', $id_region)->where("id_categorie", $id_categorie)->first();
        if ($regions_categorie) {
            $this->extimation_prix = $regions_categorie->prix + $prix;
            
        }
    }

    




    public function render()
    {
        $post = posts::where('id', $this->id)->where("id_user", Auth::user()->id)->first();
        if ($post) {
            $this->titre = $post->titre;
            $this->description = $post->description;
            $this->region = $post->id_region;
            $this->categorie = $post->id_categorie;
            $this->prix = $post->prix;
            $this->old_photos = $post->photos;
            $this->post = $post;
        }

        $categories = categories::Orderby("order")->get(['id', 'titre']);
        $regions = regions::all(['id', 'nom']);
        return view('livewire.user.create-post')
            ->with('regions', $regions)
            ->with("categories", $categories);
    }

    //validation with multi upload image
    protected $rules = [
        'titre' => 'required|min:2',
        'description' => 'required',
        'photos.*' => 'image|max:2048|min:1',
        'region' => 'required|integer|exists:regions,id',
        'prix' => 'required|numeric|min:1',
        'prix_achat' => 'required|numeric|min:1',
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

        $jsonProprietes = $this->article_propriete;


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

            $post->photos = $data;
        }

        // Mettre à jour les autres données du post
        $post->titre = $this->titre;
        $post->description = $this->description;
        $post->id_region = $this->region;
        $post->etat = $this->etat;
        $post->proprietes =  $jsonProprietes ?? [];
        $post->id_sous_categorie = $this->id_sous_categorie;
        $post->prix_achat = $this->prix_achat;
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

        $this->dispatch('alert', ['message' => "Le post a été créé avec succès",'type'=>'success']);
        session()->flash("success", "Le post a été créé avec succès. Vous recevrez une notification une fois la publication validée par un administrateur.");

        // Réinitialiser le formulaire
        $this->reset(['titre', 'description', 'region', 'categorie', 'prix', 'etat', 'photos']);
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
