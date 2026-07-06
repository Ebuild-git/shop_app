<?php

namespace App\Livewire\Admin;

use App\Models\proprietes;
use Livewire\Component;

class UpdatePropriete extends Component
{
    public $nom, $type, $proprietes, $propriete, $typeselected, $affichage;
    public $optionsCases = [];

    public function mount($propriete)
    {
        $this->propriete    = $propriete;
        $this->nom          = $propriete->nom;
        $this->typeselected = $propriete->type;
        $this->affichage    = $propriete->affichage;

        $this->optionsCases = collect($propriete->options ?? [])
            ->map(function ($option, $key) {
                if (is_array($option)) {
                    return array_merge([
                        'value'    => 'option' . ($key + 1),
                        'titre'    => '',
                        'title_en' => '',
                        'title_ar' => '',
                    ], $option);
                }

                // legacy plain-string option, e.g. "Cuir"
                return [
                    'value'    => $option,
                    'titre'    => $option,
                    'title_en' => '',
                    'title_ar' => '',
                ];
            })
            ->values()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.admin.update-propriete');
    }

    public function updatedTypeselected($value)
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

    public function delete_option($key)
    {
        unset($this->optionsCases[$key]);
        $this->optionsCases = array_values($this->optionsCases);
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

    public function update()
    {
        $rules = [
            'nom'       => 'required',
            'affichage' => 'nullable|in:case,input',
        ];

        if ($this->typeselected === 'option') {
            $rules['optionsCases']           = 'required|array|min:1';
            $rules['optionsCases.*.titre']    = 'required|string';
            $rules['optionsCases.*.title_en'] = 'nullable|string';
            $rules['optionsCases.*.title_ar'] = 'nullable|string';
        }

        $this->validate($rules, [
            'optionsCases.*.titre.required' => __('option_title_required'),
        ]);

        $propriete = proprietes::find($this->propriete->id);
        if ($propriete) {
            $propriete->nom  = $this->nom;
            $propriete->type = $this->typeselected;

            if ($this->typeselected == "option") {
                $propriete->affichage = $this->affichage;
                $propriete->options   = $this->optionsCases;
            }

            $propriete->save();

            $this->dispatch('alert', ['message' => "La propriété a bien été modifiée !", 'type' => 'success']);
        }
    }
}
