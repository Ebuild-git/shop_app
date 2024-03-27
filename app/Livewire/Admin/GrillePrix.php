<?php

namespace App\Livewire\Admin;

use App\Models\categories;
use App\Models\regions_categories;
use Livewire\Component;

class GrillePrix extends Component
{

    public $categories,$categorie;

    public function render()
    {
        $this->categories = categories::all(['id','titre']);
        $regions_categories = regions_categories::orderBy("id", "desc");
        if (!empty($this->categorie)) {
            $regions_categories = $regions_categories->where(function ($query) {
                $query->where('id_categorie', 'like', '%' . $this->categorie . '%');
            });
        }
        $regions_categories = $regions_categories->paginate(50);
        
        return view('livewire.admin.grille-prix', compact("regions_categories"));

    }

    public function delete($id){
        regions_categories::find($id)->delete();
        $this->dispatch('alert', ['message' => "Région supprimé", 'type' => 'info']);
    }

    public function filtre(){
        $this->render();
    }
}
