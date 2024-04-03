<?php

namespace App\Livewire;

use App\Models\categories;
use App\Models\proprietes;
use App\Models\sous_categories;
use Livewire\Component;

class CreateSousCategorie extends Component
{
    public $titre, $description, $id_categorie, $proprietes;
    public $proprios = [];




    public function mount($id_categorie)
    {
        $this->id_categorie = $id_categorie;
    }

    public function render()
    {

        $this->proprietes = proprietes::all();
        $liste = sous_categories::where('id_categorie', $this->id_categorie)->orderBy('order')->get();
        return view('livewire.create-sous-categorie', compact("liste"));
    }

    protected $rules = [
        'titre' => 'required|min:3',
    ];

    public function save()
    {
        $this->validate();


        //recuperation des proprietes
        $indexes = array_keys($this->proprios, true);
        $indexesArray = [];
        foreach ($indexes as $index) {
            $indexesArray[] = $index;
        }
        $jsonIndexes = $indexesArray;


        $sous_categorie = new sous_categories();
        $sous_categorie->titre = $this->titre;
        $sous_categorie->id_categorie = $this->id_categorie;
        $sous_categorie->proprietes = $jsonIndexes ?? [];
        $sous_categorie->save();


        $this->dispatch('alert', ['message' => "La sous catégorie a bien été ajoutée", 'type' => 'success']);

        //reset form
        $this->reset(['titre', 'description']);

        $this->dispatch('categorieCreated');
    }

    public function delete($id)
    {
        sous_categories::find($id)->delete();
        $this->dispatch('alert', ['message' => "La sous catégorie a bien été supprimée", 'type' => 'info']);
    }
}
