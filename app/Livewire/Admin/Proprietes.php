<?php

namespace App\Livewire\Admin;

use App\Models\proprietes as ModelsProprietes;
use Livewire\Component;

class Proprietes extends Component
{
    public $type, $nom, $prorietes, $typeselected, $required;
    public $optionsCases = [];

    public function render()
    {
        $this->prorietes = ModelsProprietes::Orderby("order")->get();
        return view('livewire.admin.proprietes');
    }

    public function updatedType($value)
    {
        $this->typeselected = $value;
        $this->optionsCases = ['option'];
    }


    public function create()
    {
        $this->validate([
            'type' => 'required',
            'nom' => 'required',
            'required' => 'required|in:1,0'
        ]);
        $propriete = new ModelsProprietes();
        $propriete->type = $this->type;
        $propriete->required = $this->required;
        $propriete->nom = $this->nom;
        if($this->type == "option"){
            $propriete->options = json_encode($this->optionsCases);
        }
        $propriete->save();
        session()->flash("success", "La propriété a été ajoutée avec succès");
        //reset input
        $this->resetInput();
        $this->reset();
    }

    private function resetInput()
    {
        $this->type = null;
        $this->nom = null;
    }


    public function add_option()
    {
        // Ajouter une nouvelle option de case à cocher
        $newOption = 'option' . (count($this->optionsCases) + 1);
        $this->optionsCases[] = $newOption;
    }

    public function delete($id)
    {
        if ($id) {
            ModelsProprietes::find($id)->delete();
            session()->flash("success", "La propriété a été supprimé avec succès");
        }
    }
}
