<?php

namespace App\Livewire\Admin;

use App\Models\regions_categories;
use Livewire\Component;

class GrillePrix extends Component
{

    public $regions_categories;

    public function render()
    {
        $this->regions_categories = regions_categories::all();
        return view('livewire.admin.grille-prix');
    }

    public function delete($id){
        regions_categories::find($id)->delete();
        $this->dispatch('alert', ['message' => "Région supprimé", 'type' => 'info']);
    }
}
