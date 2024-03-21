<?php

namespace App\Livewire;

use App\Models\categories;
use App\Models\proprietes;
use App\Models\regions;
use App\Models\regions_categories;
use Livewire\WithFileUploads;
use Livewire\Component;

class FormCreateCategorie extends Component
{
    use WithFileUploads;
    public $titre, $description, $photo,  $pourcentage_gain, $proprietes, $list_regions;
    public $proprios = [];
    public $regions = [];
    protected $listeners = ['regionCreated' => '$refresh'];


    public function render()
    {
        $this->proprietes = proprietes::all();
        $this->list_regions = regions::all('id', 'nom');
        return view('livewire.form-create-categorie');
    }

    public function creer()
    {



        $this->validate([
            'titre' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'photo' => 'required|image|mimes:jpg,png,jpeg,webp|max:10048',
            'pourcentage_gain' => 'numeric|nullable|min:0',
        ]);


        $indexes = array_keys($this->proprios, true);

        $indexesArray = [];
        foreach ($indexes as $index) {
            $indexesArray[] = $index;
        }
        $jsonIndexes = $indexesArray;

        $newName = $this->photo->store('uploads/categories', 'public');

        $categorie = new categories(); // Assurez-vous que le nom de la classe modèle est correct
        $categorie->titre = $this->titre;
        $categorie->description = $this->description;
        $categorie->icon = $newName;
        $categorie->proprietes = $jsonIndexes ?? [];
        $categorie->pourcentage_gain = $this->pourcentage_gain ?? 0;
        if ($categorie->save()) {
            foreach ($this->regions as $cle => $valeur) {
                $regions_categorie = new regions_categories();
                $regions_categorie->id_region = $cle;
                $regions_categorie->id_categorie = $categorie->id;
                $regions_categorie->prix =  $valeur;
                $regions_categorie->save();
            }
            $this->reset(['titre', 'description', 'photo']);
            session()->flash("success", "La catégorie a été ajoutée avec succès");
            $this->list_regions = "";
            $this->dispatch('categorieCreated');
        } else {
            session()->flash("error", "Une erreur est survenue lors de l'ajout de la catégorie. Veuillez réessayer plus tard.");
        }
    }
}
