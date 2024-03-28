<?php

namespace App\Livewire\Admin;

use App\Models\proprietes as ModelsProprietes;
use Livewire\Component;

class Proprietes extends Component
{
    public $type, $nom, $prorietes, $typeselected, $required,$affichage;
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
            'affichage' =>'nullable|in:case,input'

        ]);
        $propriete = new ModelsProprietes();
        $propriete->type = $this->type;
        $propriete->nom = $this->nom;
        if($this->type == "option"){
            $propriete->affichage = $this->affichage ;
            $propriete->options = $this->optionsCases;
        }
        $propriete->save();
        $this->dispatch('alert', ['message' => "La propriété a été ajoutée avec succès", 'type' => 'success']);
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
            $this->dispatch('alert', ['message' => "La propriété a été supprimé avec succès", 'type' => 'info']);
        }
    }
}
