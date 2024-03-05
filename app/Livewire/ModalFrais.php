<?php

namespace App\Livewire;

use App\Models\configurations;
use Livewire\Component;

class ModalFrais extends Component
{
    public $configuration, $pourcentage_gain, $frais_livraison;

    public function mount()
    {
        $this->configuration = Configurations::firstOrNew();
        $this->pourcentage_gain = $this->configuration->pourcentage_gain ?? 0;
        $this->frais_livraison = $this->configuration->frais_livraison ??  0;
    }

    public function render()
    {
        return view('livewire.modal-frais');
    }

    protected $rules = [
        'pourcentage_gain' => 'nullable|numeric|min:0|max:100',
        'frais_livraison' => 'nullable|numeric|min:0|min:8'
    ];

    public function enregistrer()
    {
        $this->validate();
        //reset flash message
        session()->forget(['success', 'error']);
        $config = Configurations::find($this->configuration->id);
        $config->pourcentage_gain = $this->pourcentage_gain;
        $config->frais_livraison = $this->frais_livraison;
        if ($config->save()) {
            session()->flash("success", "La modification a été effectuée avec succès");
        } else {
            session()->flash("error", "Une erreur est survenue lors de la modification");
        }
    }
}
