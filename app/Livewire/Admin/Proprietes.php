<?php

namespace App\Livewire\Admin;

use App\Models\proprietes as ModelsProprietes;
use Livewire\Component;

class Proprietes extends Component
{
    public $type, $nom, $prorietes;
    public function render()
    {
        $this->prorietes = ModelsProprietes::all();
        return view('livewire.admin.proprietes');
    }


    public function create()
    {
        $this->validate([
            'type' => 'required',
            'nom' => 'required',
        ]);
        $propreite = new ModelsProprietes();
        $propreite->type = $this->type;
        $propreite->nom = $this->nom;
        $propreite->save();
        session()->flash("success", "La propriété a été ajoutée avec succès");
        //reset input
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->type = null;
        $this->nom = null;
    }


    public function delete($id){
        if($id){
            ModelsProprietes::find($id)->delete();
            session()->flash("success", "La propriété a été supprimé avec succès");
        }
    }
}
