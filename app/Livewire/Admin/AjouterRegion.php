<?php

namespace App\Livewire\Admin;

use App\Models\regions;
use Livewire\Component;

class AjouterRegion extends Component
{

    public $nom, $regions;

    protected $rules = [
        'nom' => ['required', 'string'],
    ];


    public function addRegion()
    {
        $this->validate();
        //check if nom is unnique
        if (regions::where('nom', '=', $this->nom)->exists()) {
            session()->flash('error', "La région {$this->nom} existe déjà.");
            $this->dispatch('alert', ['message' => "La région {$this->nom} existe déjà !", 'type' => 'warning']);
        } else {
            $region = new regions();
            $region->nom = $this->nom;
            if ($region->save()) {
                session()->flash('success', "La région a été ajoutée avec succès");
                $this->nom = "";
                $this->dispatch('regionCreated');
                $this->dispatch('alert', ['message' => "La région a été ajoutée avec succès", 'type' => 'info']);
            } else {
                $this->dispatch('alert', ['message' => "Une erreur est survenue !", 'type' => 'warning']);
                session()->flash('error', "Une erreur est survenue lors de l'ajout de la région, veuillez réessayer plus tard.");
            }
        }
    }

    public function delete($id)
    {
        if (!is_null($id)) {
            $region = regions::find($id);
            $region->delete();
            session()->flash("success", "La région a bien été supprimé!");
            $this->dispatch('alert', ['message' => "La région a bien été supprimé!", 'type' => 'info']);
            $this->dispatch('regionCreated');
        }
    }

    public function render()
    {
        $this->regions = regions::all();
        return view('livewire.admin.ajouter-region');
    }
}
