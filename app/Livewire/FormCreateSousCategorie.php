<?php

namespace App\Livewire;

use App\Models\proprietes;
use App\Models\sous_categories;
use Livewire\Component;

class FormCreateSousCategorie extends Component
{

    public $proprios = [];
    public $required = [];
    public $titre, $proprietes, $id_categorie;

    public function render()
    {
        $this->proprietes = proprietes::all();
        return view('livewire.form-create-sous-categorie');
    }

    public function mount($id_categorie)
    {
        $this->id_categorie = $id_categorie;
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
        $test = [];
        foreach ($indexes as $index) {
            $indexesArray[] = $index;
            $status = $this->required[$index] ?? 'Non';
            $test[] = [
                'id' => $index,
                'required' => $status,
            ];
        }
        $jsonIndexes = $indexesArray;
        $sous_categorie = new sous_categories();
        $sous_categorie->titre = $this->titre;
        $sous_categorie->id_categorie = $this->id_categorie;
        $sous_categorie->proprietes = $jsonIndexes ?? [];
        $sous_categorie->required = json_encode($test) ?? [];
        $sous_categorie->save();


        //flash message
        session()->flash('success', 'La sous catégorie a bien été ajoutée');

        //reset form
        $this->reset(['titre']);
        $this->proprios = [];
        $this->required = [];

        $this->dispatch('categorieCreated');
    }

}
