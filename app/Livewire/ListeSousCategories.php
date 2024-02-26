<?php

namespace App\Livewire;

use App\Models\sous_categories;
use Livewire\Component;

class ListeSousCategories extends Component
{
    protected $listeners = ['categorieCreated' => '$refresh'];

    public function render()
    {
        $lists = sous_categories::all();
        return view('livewire.liste-sous-categories')->with("lists",$lists);
    }

    public function delete($id){
        $sous_cat = sous_categories::find($id);
        if($sous_cat)
        {
            $sous_cat->delete();
            //success mesage
            session()->flash('success', 'La sous catégorie a été supprimée avec succès');
            $this->dispatch('categorieCreated');
        }
    }
}
