<?php

namespace App\Livewire;

use App\Models\configurations;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class NosPartenaires extends Component
{
    use WithFileUploads;
    public $logo;

    public function render()
    {
        $configuration = configurations::first();
        $logos = json_decode($configuration->partenaires) ?? [];
        return view('livewire.nos-partenaires' , compact('logos'));
    }

    public function delete($url){
        $configuration = configurations::first();
        $logos = json_decode($configuration->partenaires, true) ?? []; 
        $index = array_search($url, $logos);
        if($index !== false) {
            unset($logos[$index]);

            //delete logo image with Storage
            Storage::disk('public')->delete($url);

            $configuration->partenaires = json_encode($logos);
            $configuration->save();
        }
    }
    

    public function create(){
        //validation de l'image logo
        $this->validate([
            'logo' => ['required', 'image','Max:3000'],
        ]);
        $configuration = configurations::first();
        $logo = $this->logo->store('uploads/partenaires', 'public');
        $logos = json_decode($configuration->partenaires) ?? [];
        array_push($logos, $logo);
        $configuration->partenaires = json_encode($logos);
        $configuration->save();
        $this->logo = null;
    }
}
