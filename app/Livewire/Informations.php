<?php

namespace App\Livewire;

use App\Models\configurations;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\Component;

class Informations extends Component
{
    use WithFileUploads;

    public $email, $telephone, $tiktok, $instagram, $facebook, $linkedin, $logo, $logo2;

   
    public function render()
    {
        $configuration = configurations::first();
        $this->facebook = $configuration->facebook;
        $this->email = $configuration->email;
        $this->tiktok = $configuration->tiktok;
        $this->instagram = $configuration->instagram;
        $this->linkedin = $configuration->linkedin;
        $this->telephone = $configuration->telephone;
        $this->logo = $configuration->logo;
        $this->logo2 = $configuration->logo;

        return view('livewire.informations');
    }



    public function update()
    {
        //validation du formulaire
        $this->validate([
            'email' => ['nullable', 'string'],
            'telephone' => ['nullable', 'string'],
            'tiktok' => ['nullable', 'url'],
            'facebook' => ['nullable', 'url'],
            'instagram' => ['nullable', 'url'],
            'linkedin' => ['nullable', 'url'],
           /// 'logo'  => 'nullable','image','mimes:jpeg,png,jpg,gif,svg','max:1000',
        ]);
    

        //verifier si une configuration est deja presente si c'est pas le cas creer une nouvelle
        $old_configuraion = configurations::first();
        if (!$old_configuraion) {
            $old_configuraion = new configurations();
        }

        $config = $old_configuraion::find($old_configuraion->id);
        //check if image is selected
       /*  if ($this->logo) {
            if ($old_configuraion->logo) {
                Storage::disk('public')->delete($old_configuraion->logo);
            }
            $newName = $this->logo->store('uploads/configuration', 'public');
            $config->logo = $newName;
        } */

        $config->facebook = $this->facebook;
        $config->instagram = $this->instagram;
        $config->linkedin = $this->linkedin;
        $config->tiktok = $this->tiktok;
        $config->phone_number   = $this->telephone;
        $config->email = $this->email;
        $config->save();

        //show success message
        session()->flash('success', __('Information mises à jour avec succès'));
    }
}
