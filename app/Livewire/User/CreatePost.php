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
    public $colors, $required = [];
    public $selected_color = null;
    public $text_name_color;
    public $article_propriete = [];
    public $proprietes, $quantite;
    protected $listeners = ['suggestionSelected'];
    public $data_post;


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
        } else {
            $this->selectedCategory = null;
        }
    }


    public function choose($nom, $code, $propriete_nom)
    {
        $this->selected_color = $nom;
        $this->text_name_color = $nom;
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
    }



    public function updatedPrix($value)
    {
        $this->prix = $value;
    }



    public function updatedPtitre($value)
    {
        $this->titre = $value;
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

        $categories = categories::Orderby("order")->get(['id', 'titre', 'luxury']);
        $regions = regions::all(['id', 'nom']);
        return view('livewire.user.create-post')
            ->with('regions', $regions)
            ->with("categories", $categories);
    }

    //validation with multi upload image



    public function inputChanged($value)
    {
        $this->prix = $value;
    }




    public function before_post()
    {
        try {
            $this->validate([
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
            ], [
                'required' => "Ce champ est obligatoire"
            ]);


        } catch (\Illuminate\Validation\ValidationException $e) {
            // Les erreurs sont automatiquement sur chaque babge error de chaque input
            $this->dispatch('alert', ['message' => "", 'type' => 'warning']);

            $errors = $e->validator->getMessageBag();
            foreach ($errors->keys() as $field) {
                foreach ($errors->get($field) as $message) {
                    $this->addError($field, $message);
                }
            }

            return;

        }





        $sous_categorie = sous_categories::find($this->selectedSubcategory);
        if ($sous_categorie->categorie->luxury == 1) {
            if ($this->prix < 800) {
                //le prix doit dépasse 800 DH 
                $this->addError('prix', 'Le prix de vente doit dépasser les 800 DH pour être ajouter a la catégorie LUXURY');
                return;
            }
        }



        $jsonProprietes = $this->article_propriete ?? [];



        $photos = [];
        if ($this->photo1) {
            $name = $this->photo1->store('uploads/posts', 'public');
            $photos[] = $name;
        }
        if ($this->photo2) {
            $name = $this->photo2->store('uploads/posts', 'public');
            $photos[] = $name;
        }
        if ($this->photo3) {
            $name = $this->photo3->store('uploads/posts', 'public');
            $photos[] = $name;
        }
        if ($this->photo4) {
            $name = $this->photo4->store('uploads/posts', 'public');
            $photos[] = $name;
        }
        if ($this->photo5) {
            $name = $this->photo5->store('uploads/posts', 'public');
            $photos[] = $name;
        }



        $data_post = [
            "titre" => $this->titre,
            "description" => $this->description,
            "photos" => $photos,
            "etat" => $this->etat,
            "proprietes" => $jsonProprietes,
            "id_sous_categorie" => $this->selectedSubcategory,
            "sous_categorie" => sous_categories::find($this->selectedSubcategory),
            "categorie" => sous_categories::find($this->selectedSubcategory)->categorie,
            "id_region" => $this->region,
            "prix" => $this->prix,
            "id_user" => Auth::user()->id,
            "statut" => "vente",
            "prix_achat" => $this->prix_achat ?? 0,
            "created_at" => now(),
        ];

        $this->data_post = $data_post;
    }




    public function preview()
    {
        $this->before_post();
        if ($this->data_post) {
            //verifier que l'utilisateur a ajouter au moins une photo
            if (empty($this->data_post["photos"])) {
                $this->dispatch('alert', ['message' => "Vous devez ajouter au moins une photo!", 'type' => 'warning']);
                return;
            } else {
                $this->dispatch('openmodalpreview', $this->data_post);
                return;
            }
        } else {
            $this->dispatch('alert2', ['message' => "Veuillez remplir tous les champs obligatoires avant de publier !", 'type' => 'warning','time'=>5000]);
            return;
        }
    }






    public function submit()
    {
        $this->before_post();
        if ($this->data_post) {
            //verifier que l'utilisateur a ajouter au moins une photo
            if (empty($this->data_post["photos"])) {
                $this->dispatch('alert', ['message' => "Vous devez ajouter au moins une photo!", 'type' => 'warning']);
                return;
            } else {
                $this->make_post($this->data_post);
            }
        } else {
            $this->dispatch('alert', ['message' => "Erreur de prévicualisation !", 'type' => 'warning']);
            return;
        }
    }




    public function make_post($data)
    {
        $config = configurations::first();
        $post = new posts();
        $post->photos = $data["photos"];
        $post->titre = $this->titre;
        $post->description = $this->description;
        $post->id_region = $this->region;
        $post->etat = $this->etat;
        $post->proprietes = $data["proprietes"];
        $post->id_sous_categorie = $this->selectedSubcategory;
        if ($this->prix_achat > 0) {
            $post->prix_achat = $this->prix_achat;
        }
        $post->prix = $this->prix;
        $post->id_user = Auth::user()->id;
        if ($config->valider_publication == 0) {
            $post->verified_at = now();
            $post->statut = 'vente';
        }
        $post->save();


        if ($config->valider_publication == 1) {
            event(new AdminEvent('Un post a été créé avec succès.'));
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

        //$this->dispatch('alert', ['message' => "Le post a été créé avec succès", 'type' => 'success']);

        // Réinitialiser le formulaire
        return redirect()->route('details_post_single', ['id' => $post->id]);
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
