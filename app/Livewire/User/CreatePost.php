<?php

namespace App\Livewire\User;

use App\Events\AdminEvent;
use App\Models\categories;
use App\Models\configurations;
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
use App\Traits\ListColor;

class CreatePost extends Component
{
    use WithFileUploads;
    use ListColor;

    public $titre, $description, $region, $categorie, $sous_categories, $prix, $id, $prix_achat, $post, $old_photos, $id_sous_categorie, $etat, $selectedCategory, $selectedSubcategory;
    public $photo1, $photo2, $photo3, $photo4, $photo5;
    public $colors,$required;
    public $selected_color = null;
    public $article_propriete = [];
    public $proprietes, $quantite;
    public $extimation_prix = 0;
    protected $listeners = ['suggestionSelected'];


    public function mount($id)
    {
        $this->id = $id;
        $this->colors = $this->get_list_color();
    }

    public function updatedSelectedCategory($value)
    {
        if ($value != "x") {
            $c = sous_categories::where("id_categorie", $value)->get();
            $this->sous_categories = $c;
            if (!is_null($value) && !is_null($this->region) && !is_null($this->prix)) {
                $this->calcule_estimation($this->region, $value, $this->prix);
            }
        } else {
            $this->selectedCategory = null;
        }
    }


    public function choose($nom,$code,$propriete_nom){
        $this->selected_color = $nom;
        $this->article_propriete[$propriete_nom] = $code;
    }


    public function updatedselectedSubcategory($value)
    {
        if ($value != "x") {
            $sous_categorie = sous_categories::find($value);
            if ($sous_categorie) {
                $this->proprietes = $sous_categorie->proprietes;
                $this->required = $sous_categorie->required;
            }
        } else {
            $this->proprietes = null;
            $this->id_sous_categorie = null;
        }
    }



    public function updatedRegion($value)
    {
        $this->region = $value;
        if (!is_null($value) && !is_null($this->selectedCategory) && !is_null($this->prix)) {
            $this->calcule_estimation($value, $this->selectedCategory, $this->prix);
        }
    }



    public function updatedPrix($value)
    {
        $this->prix = $value;
        if (!is_null($value) && !is_null($this->selectedCategory) && !is_null($this->prix)) {
            $this->calcule_estimation($this->region, $this->selectedCategory, $value);
        }
    }



    public function updatedPtitre($value)
    {
        $this->titre = $value;
    }



    public function calcule_estimation($id_region, $id_categorie, $prix)
    {
        $regions_categorie = regions_categories::where('id_region', $id_region)->where("id_categorie", $id_categorie)->first();
        if ($regions_categorie) {
            $this->extimation_prix = $regions_categorie->prix + $prix;
        }
    }


    public function suggestionSelected($name, $value)
    {
        if ($value != "") {
            $this->article_propriete[$name] = $value;
        }
    }


    public function reset_photo1()
    {
        $this->photo1 = null;
    }
    public function reset_photo2()
    {
        $this->photo2 = null;
    }
    public function reset_photo3()
    {
        $this->photo3 = null;
    }
    public function reset_photo4()
    {
        $this->photo4 = null;
    }
    public function reset_photo5()
    {
        $this->photo5 = null;
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

        $categories = categories::Orderby("order")->get(['id', 'titre','luxury']);
        $regions = regions::all(['id', 'nom']);
        return view('livewire.user.create-post')
            ->with('regions', $regions)
            ->with("categories", $categories);
    }

    //validation with multi upload image
    protected $rules = [
        'titre' => 'required|min:2',
        'description' => 'string|nullable',
        'photo1' => 'nullable|max:2048|min:1',
        'photo2' => 'nullable|max:2048|min:1',
        'photo3' => 'nullable|max:2048|min:1',
        'photo4' => 'nullable|max:2048|min:1',
        'region' => 'required|integer|exists:regions,id',
        'prix' => 'required|numeric|min:1',
        'prix_achat' => 'nullable|numeric|min:1',
        'etat' => ['required', 'string'],
        'selectedSubcategory' => 'required|integer|exists:sous_categories,id'
    ];


    public function inputChanged($value)
    {
        $this->prix = $value;
    }




    public function submit()
    {
        $this->validate(); // Vous validez les données soumises
        $config = configurations::first();

        $jsonProprietes = $this->article_propriete;


        $post = posts::find($this->post->id ?? "d"); // Vous cherchez le post existant par son ID
        if (!$post) {
            $post = new posts(); // Si le post n'existe pas, vous en créez un nouveau
        }



        // Traitement des photos
        $data = [];
        if ($this->photo1) {
            $name = $this->photo1->store('uploads/posts', 'public');
            $data[] = $name;
        }
        if ($this->photo2) {
            $name = $this->photo2->store('uploads/posts', 'public');
            $data[] = $name;
        }
        if ($this->photo3) {
            $name = $this->photo3->store('uploads/posts', 'public');
            $data[] = $name;
        }
        if ($this->photo4) {
            $name = $this->photo4->store('uploads/posts', 'public');
            $data[] = $name;
        }
        if ($this->photo5) {
            $name = $this->photo5->store('uploads/posts', 'public');
            $data[] = $name;
        }
        $post->photos = $data;



        // Mettre à jour les autres données du post
        $post->titre = $this->titre;
        $post->description = $this->description;
        $post->id_region = $this->region;
        $post->etat = $this->etat;
        $post->proprietes = $jsonProprietes ?? [];
        $post->id_sous_categorie = $this->selectedSubcategory;
        $post->prix_achat = $this->prix_achat;
        $post->prix = $this->prix;
        $post->id_user = Auth::user()->id;
        if ($config->valider_publication === false) {
            $post->verified_at = now();
            $post->statut = 'vente';
        }
        $post->save(); // Sauvegarder le post


        if ($config->valider_publication == 1 ) {
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
        }

        $this->dispatch('alert', ['message' => "Le post a été créé avec succès", 'type' => 'success']);
        session()->flash("success", "Le post a été créé avec succès. Vous recevrez une notification une fois la publication validée par un administrateur.");

        // Réinitialiser le formulaire
        $this->reset(['titre', 'description', 'region', 'categorie', 'prix', 'etat', 'photo1', 'photo2', 'photo3', 'photo4', 'photo5']);
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
            $post->photos = json_encode($photosArray);
            $post->save();
        }
    }
}
