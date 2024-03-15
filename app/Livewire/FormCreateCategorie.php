<?php

namespace App\Livewire;

use App\Models\categories;
use App\Models\proprietes;
use Livewire\WithFileUploads;
use Livewire\Component;

class FormCreateCategorie extends Component
{
    use WithFileUploads;
    public $titre, $description, $photo, $frais_livraison, $pourcentage_gain, $proprietes;
    public $proprios = [];

    public function render()
    {
        $this->proprietes = proprietes::all();
        return view('livewire.form-create-categorie');
    }

    public function creer()
    {
        $this->validate([
            'titre' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'photo' => 'required|image|mimes:jpg,png,jpeg,webp|max:10048',
            'frais_livraison' => 'numeric|nullable|min:0',
            'pourcentage_gain' => 'numeric|nullable|min:0',
        ]);


        $indexes = array_keys($this->proprios, true);
        $indexesArray = [];
        foreach ($indexes as $index) {
            $indexesArray[] = $index;
        }
        $jsonIndexes = json_encode($indexesArray);

        $newName = $this->photo->store('uploads/categories', 'public');

        $categorie = new categories(); // Assurez-vous que le nom de la classe modèle est correct
        $categorie->titre = $this->titre;
        $categorie->description = $this->description;
        $categorie->icon = $newName;
        $categorie->frais_livraison = $this->frais_livraison ?? 0;
        $categorie->proprietes = $jsonIndexes ?? [];
        $categorie->pourcentage_gain = $this->pourcentage_gain ?? 0;
        $categorie->save();
        $this->reset(['titre', 'description', 'photo']);
        session()->flash("success", "La catégorie a été ajoutée avec succès");
        $this->dispatch('categorieCreated');
    }
}
