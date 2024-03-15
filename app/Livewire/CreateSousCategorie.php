<?php

namespace App\Livewire;

use App\Models\categories;
use App\Models\sous_categories;
use Livewire\Component;

class CreateSousCategorie extends Component
{
    public $titre, $description, $id_categorie;

    public function mount($id_categorie)
    {
        $this->id_categorie = $id_categorie;
    }

    public function render()
    {
        $liste = sous_categories::where('id_categorie',$this->id_categorie)->get();
        return view('livewire.create-sous-categorie', compact("liste"));
    }

    protected $rules = [
        'titre' => 'required|min:3',
        'description' => 'nullable|string',
    ];

    public function save()
    {
        $this->validate();

        $sous_categorie = new sous_categories();
        $sous_categorie->titre = $this->titre;
        $sous_categorie->description = $this->description;
        $sous_categorie->id_categorie = $this->id_categorie;
        $sous_categorie->save();
        session()->flash("success", "La sous catégorie a bien été ajoutée");

        //reset form
        $this->reset(['titre', 'description']);

        $this->dispatch('categorieCreated');
    }

    public function delete($id){
        sous_categories::find( $id )->delete();
        session()->flash("info", " La sous catégorie a bien été supprimée");
    }
}
