<?php

namespace App\Livewire;

use App\Models\categories;
use App\Models\sous_categories;
use Livewire\Component;

class CreateSousCategorie extends Component
{
    public $titre,$description,$categorie;
    protected $listeners = ['categorieCreated' => '$refresh'];
    
    public function render()
    {
        $categories = categories::all(["id","titre"]);
        return view('livewire.create-sous-categorie')->with('categories',$categories);
    }
    
    protected $rules=[
        'titre'=>'required|min:3',
        'description'=>'nullable|string',
        'categorie'=>'required|integer|exists:categories,id'
    ];

    public function save(){
        $this->validate();

        $sous_categorie = new sous_categories();
        $sous_categorie->titre = $this->titre;
        $sous_categorie->description = $this->description;
        $sous_categorie->id_categorie = $this->categorie;
        $sous_categorie->save();
        session()->flash("success", "La sous catégorie a bien été ajoutée");

        //reset form
        $this->reset(['titre','description']);

        $this->dispatch('categorieCreated');
    }
}
