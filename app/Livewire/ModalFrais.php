<?php

namespace App\Livewire;

use App\Models\configurations;
use Livewire\Component;

class ModalFrais extends Component
{
    public $configuration, $valeur;

    public function mount()
    {
        $this->configuration = Configurations::firstOrNew();
        $this->valeur = $this->configuration->frais_livraison ?? 0;
    }

    public function render()
    {
        return view('livewire.modal-frais');
    }

    public function enregistrer()
    {
        //reset flash message
        session()->forget(['success','error']);
        $config = Configurations::find($this->configuration->id);
        $config->frais_livraison = $this->valeur;
        if ($config->save()) {
            session()->flash("success", "La modification a été effectuée avec succès");
        } else {
            session()->flash("error", "Une erreur est survenue lors de la modification");
        }
    }
}
