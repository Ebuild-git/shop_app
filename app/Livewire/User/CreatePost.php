<?php

namespace App\Livewire\User;

use App\Events\AdminEvent;
use App\Models\categories;
use App\Models\configurations;
use App\Models\notifications;
use Livewire\WithFileUploads;
use App\Models\posts;
use App\Models\regions;
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
    public $selected_color_code;
    public $article_propriete = [];
    public $proprietes, $quantite;
    protected $listeners = ['suggestionSelected'];
    public $data_post;
    public $userHasRib;
    public $step = 1;  // New variable to manage steps


    public function mount($id)
    {
        $this->id = $id;
        $this->colors = $this->get_list_color();
        $this->userHasRib = Auth::user()->rib_number ? true : false;

    }

    public function updatedSelectedCategory($value)
    {
        if ($value != "x") {
            $c = sous_categories::where("id_categorie", $value)
                ->orderby('titre', 'Asc')
                ->get();
            $this->sous_categories = $c;
        } else {
            $this->selectedCategory = null;
            $this->sous_categories = [];
        }
        $this->validateCategoryPrice();
    }


    public function choose($nom, $code, $propriete_nom)
    {
        $this->selected_color = $nom;
        // $this->text_name_color = $nom;
        $this->selected_color_code = $code; // Store the selected color code
        $this->article_propriete[$propriete_nom] = $code;
    }


    public function updatedselectedSubcategory($value)
    {
        $this->article_propriete = [];

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

        $categories = categories::where('active', true)->orderBy('order', 'asc')->get(['id', 'titre', 'title_en', 'title_ar', 'luxury']);
        $regions = regions::all(['id', 'nom']);

        return view('livewire.user.create-post')
            ->with('regions', $regions)
            ->with("categories", $categories);
    }



    public function inputChanged($value)
    {
        $this->prix = $value;
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'selectedCategory' || $propertyName === 'prix') {
            $this->validateCategoryPrice();
        }
    }

    public function validateCategoryPrice()
    {
        logger('validateCategoryPrice called');

        $category = categories::find($this->selectedCategory);
        if (!$category)
            return;

        if ($category->luxury && $this->prix < 800) {
            logger('luxury category and low price');
            $this->addError('prix', __('price_luxury_error_high'));
        } elseif (!$category->luxury && $this->prix >= 800) {
            logger('non-luxury category and high price');
            $this->addError('prix', __('price_luxury_error_low'));
        } else {
            logger('price valid');
            $this->resetErrorBag('prix');
        }
    }

    public function before_post()
    {

        $subcategoryRequired = DB::table('sous_categories')
            ->where('id', $this->selectedSubcategory)
            ->value('required');
        $subcategoryRequired = json_decode($subcategoryRequired ?? '[]', true);
        $requiredProps = [];
        if (is_array($subcategoryRequired)) {
            foreach ($subcategoryRequired as $property) {
                if (isset($property['required']) && $property['required'] === 'Oui') {
                    $requiredProps[] = $property['id'];
                }
            }
        }
        $rules = [
            'titre' => 'required|min:2',
            'description' => 'string|nullable',
            'photo1' => 'nullable|min:1',
            'photo2' => 'nullable|min:1',
            'photo3' => 'nullable|min:1',
            'photo4' => 'nullable|min:1',
            'region' => 'required|integer|exists:regions,id',
            'prix' => 'required|numeric|min:50',
            'prix_achat' => 'nullable|numeric|min:50',
            'etat' => ['required', 'string'],
            'selectedSubcategory' => 'required|integer|exists:sous_categories,id',
            'selectedCategory' => 'required|integer|exists:categories,id'
        ];

        foreach ($requiredProps as $propId) {
            $propName = DB::table('proprietes')->where('id', $propId)->value('nom');
            $rules["article_propriete.$propName"] = 'required';
        }
        try {
            $this->validate($rules, [
                'required' => __('required2'),
                'prix.min' => 'Le prix doit être supérieur à 50 DH.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->getMessageBag();
            foreach ($errors->keys() as $field) {
                foreach ($errors->get($field) as $message) {
                    $this->addError($field, $message);
                }
            }
            return false;
        }
        $sous_categorie = sous_categories::find($this->selectedSubcategory);
        $category = $sous_categorie->categorie;


        $jsonProprietes = array_filter((array) $this->article_propriete, function ($value) {
            return !empty($value);
        });

        $photos = [];
        if ($this->photo1) {
            $path = \App\Services\ImageService::uploadAndConvert($this->photo1, 'uploads/posts');
            $photos[] = $path;
        }
        if ($this->photo2) {
            $path = \App\Services\ImageService::uploadAndConvert($this->photo2, 'uploads/posts');
            $photos[] = $path;
        }
        if ($this->photo3) {
            $path = \App\Services\ImageService::uploadAndConvert($this->photo3, 'uploads/posts');
            $photos[] = $path;
        }
        if ($this->photo4) {
            $path = \App\Services\ImageService::uploadAndConvert($this->photo4, 'uploads/posts');
            $photos[] = $path;
        }
        if ($this->photo5) {
            $path = \App\Services\ImageService::uploadAndConvert($this->photo5, 'uploads/posts');
            $photos[] = $path;
        }
        if (count($photos) < 3) {
            $this->addError('photos', __('min_photos'));
            return false;
        }
        $this->data_post = [
            "titre" => $this->titre,
            "description" => $this->description,
            "photos" => $photos,
            "etat" => $this->etat,
            "proprietes" => $jsonProprietes,
            "id_sous_categorie" => $this->selectedSubcategory,
            "sous_categorie" => sous_categories::find($this->selectedSubcategory),
            "categorie" => sous_categories::find($this->selectedSubcategory)->categorie,
            "id_region" => $this->region,
            "region" => regions::find($this->region),
            "prix" => $this->getPrix($this->prix),
            "id_user" => Auth::user()->id,
            "statut" => "vente",
            "prix_achat" => $this->prix_achat ?? 0,
            "created_at" => now(),
        ];

        return true;
    }



    public function preview()
    {
        $user = Auth::user();

        if (!$user->cin_img) {
            $this->dispatch('alert', [
                'message' => __('cin_required_warning'),
                'type' => 'warning'
            ]);
            return;
        }
        if (!$user->cin_approved) {
            $this->dispatch('alert', [
                'message' => __('cin_pending_warning'),
                'type' => 'warning'
            ]);
            return;
        }

        $this->validateCategoryPrice();

        if ($this->getErrorBag()->has('prix')) {
            $this->dispatch('alert', [
                'message' => $this->getErrorBag()->first('prix'),
                'type' => 'warning'
            ]);
            return;
        }

        if (!$this->before_post()) {
            if ($this->getErrorBag()->has('cin_img')) {
                $this->dispatch('alert', [
                    'message' => $this->getErrorBag()->first('cin_img'),
                    'type' => 'warning',
                ]);
            }

            if ($this->getErrorBag()->has('photos')) {
                $this->dispatch('alert', [
                    'message' => $this->getErrorBag()->first('photos'),
                    'type' => 'warning',
                ]);
            }
            if (!$this->getErrorBag()->has('prix') && !$this->getErrorBag()->has('cin_img') && !$this->getErrorBag()->has('photos')) {
                $this->dispatch('remplir-alert');
            }

            return;
        }

        if (count($this->data_post["photos"]) < 3) {
            $this->dispatch('alert', [
                'message' => __('min_photos'),
                'type' => 'warning'
            ]);
            return;
        }
        $this->dispatch('openmodalpreview', $this->data_post);
    }

    public function submit()
    {
        $this->before_post();
        if ($this->data_post) {

            if (count($this->data_post["photos"]) < 3) {
                $this->dispatch('alert', ['message' => __('min_photos'), 'type' => 'warning']);
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


        event(new AdminEvent('Un post a été créé avec succès.'));
        $notification = new notifications();
        $notification->type = "new_post";
        $notification->titre = Auth::user()->username . " vient de publier un article ";
        $notification->url = "/admin/publication/" . $post->id . "/view";
        $notification->message = $post->titre;
        $notification->id_post = $post->id;
        $notification->id_user = Auth::user()->id;
        $notification->destination = "admin";
        $notification->save();


        return redirect()->route('details_post_single', ['id' => $post->id])->with('show_validation_modal', $config->valider_publication == 1);
    }


    public function getPrix($prix)
    {
        $sous_cat = sous_categories::find($this->selectedSubcategory);
        if ($sous_cat) {
            $pourcentage_gain = $sous_cat->categorie->pourcentage_gain;
            $prix = $this->prix;
            $prix_calculé = round($prix + (($pourcentage_gain * $prix) / 100), 2);

            return number_format($prix_calculé, 2, '.', '') ?? "N/A";
        } else {
            return "N/A";
        }
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
