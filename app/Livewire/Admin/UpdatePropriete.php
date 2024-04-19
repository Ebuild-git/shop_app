<?php

namespace App\Livewire\Admin;

use App\Models\proprietes;
use Livewire\Component;

class UpdatePropriete extends Component
{

    public $nom, $type, $proprietes, $propriete,$typeselected,$affichage;
    public $optionsCases = [];


    public function mount($propriete)
    {
        $this->propriete = $propriete;
        $this->nom = $propriete->nom;
        $this->typeselected = $propriete->type;
        $this->affichage = $propriete->affichage;
        $this->optionsCases = $propriete->options;
    }

    public function render()
    {
        
        return view('livewire.admin.update-propriete');
    }


    public function updatedTypeselected($value)
    {
        $this->typeselected = $value;
        $this->optionsCases = ['option'];
    }

    public function delete_option($key){
        unset($this->optionsCases[$key]);
    }


    public function add_option()
    {
        // Ajouter une nouvelle option de case à cocher
        $newOption = 'option' . (count($this->optionsCases) + 1);
        $this->optionsCases[] = $newOption;
    }

    public function update()
    {
        $propriete = proprietes::find($this->propriete->id);
        if ($propriete) {
            $propriete->nom = $this->nom;
            $propriete->type = $this->typeselected;
            if($this->typeselected == "option"){
                $propriete->affichage = $this->affichage ;
                $propriete->options = $this->optionsCases;
            }
            $propriete->save();

            $this->dispatch('alert', ['message' => "La propriété a bien été modifié !", 'type' => 'success']);
        }

    }
}
