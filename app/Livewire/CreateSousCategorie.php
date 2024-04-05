<?php

namespace App\Livewire;

use App\Models\categories;
use App\Models\proprietes;
use App\Models\sous_categories;
use Livewire\Component;

class CreateSousCategorie extends Component
{
    public $titre, $description, $id_categorie, $proprietes;
    protected $listeners = ['categorieCreated' => '$refresh'];

  
    public function render()
    {

       
        $liste = sous_categories::where('id_categorie', $this->id_categorie)->orderBy('order')->get();
        return view('livewire.create-sous-categorie', compact("liste"));
    }

   

   
    public function delete($id)
    {
        sous_categories::find($id)->delete();
        $this->dispatch('alert', ['message' => "La sous catégorie a bien été supprimée", 'type' => 'info']);
    }
}
