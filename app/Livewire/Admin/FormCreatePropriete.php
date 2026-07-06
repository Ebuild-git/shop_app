<?php

namespace App\Livewire\Admin;

use App\Models\proprietes;
use Livewire\Component;

class FormCreatePropriete extends Component
{
    public $type, $nom, $typeselected, $required, $affichage;
    public $optionsCases = [];

    public function render()
    {
        return view('livewire.admin.form-create-propriete');
    }

    public function updatedType($value)
    {
        $this->typeselected = $value;
        $this->optionsCases = [
            [
                'value'    => 'option1',
                'titre'    => '',
                'title_en' => '',
                'title_ar' => '',
            ],
        ];
    }

    public function add_option()
    {
        $this->optionsCases[] = [
            'value'    => 'option' . (count($this->optionsCases) + 1),
            'titre'    => '',
            'title_en' => '',
            'title_ar' => '',
        ];
    }

    public function delete_option($key)
    {
        unset($this->optionsCases[$key]);
        $this->optionsCases = array_values($this->optionsCases);
    }

    public function create()
    {
        $rules = [
            'type'      => 'required',
            'nom'       => 'required',
            'affichage' => 'nullable|in:case,input',
        ];

        if ($this->type === 'option') {
            $rules['optionsCases']              = 'required|array|min:1';
            $rules['optionsCases.*.titre']       = 'required|string';
            $rules['optionsCases.*.title_en']    = 'nullable|string';
            $rules['optionsCases.*.title_ar']    = 'nullable|string';
        }

        $this->validate($rules, [
            'optionsCases.*.titre.required' => __('option_title_required'),
        ]);

        $propriete = new proprietes();
        $propriete->type = $this->type;
        $propriete->nom  = $this->nom;

        if ($this->type == "option") {
            $propriete->affichage = $this->affichage;
            $propriete->options   = $this->optionsCases;
        }

        $propriete->save();

        $this->dispatch('alert', ['message' => "La propriété a été ajoutée avec succès", 'type' => 'success']);
        $this->dispatch('created-propriete');

        session()->flash('success', "La propriété a été ajoutée avec succès");

        $this->resetInput();
        $this->reset();
    }

    private function resetInput()
    {
        $this->type = null;
        $this->nom = null;
    }

    public function delete($id)
    {
        if ($id) {
            proprietes::find($id)->delete();
            $this->dispatch('alert', ['message' => "La propriété a été supprimé avec succès", 'type' => 'info']);
        }
    }
}
