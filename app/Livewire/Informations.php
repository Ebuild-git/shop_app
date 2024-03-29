<?php

namespace App\Livewire;

use App\Models\configurations;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\Component;

class Informations extends Component
{
    use WithFileUploads;

    public $email, $telephone, $tiktok, $instagram, $facebook, $linkedin, $logo,$logo2,$adresse,$valider_publication,$valider_photo;


    public function render()
    {
        $configuration = configurations::firstorCreate();
        $this->facebook = $configuration->facebook;
        $this->email = $configuration->email;
        $this->tiktok = $configuration->tiktok;
        $this->adresse = $configuration->adresse;
        $this->instagram = $configuration->instagram;
        $this->linkedin = $configuration->linkedin;
        $this->telephone = $configuration->phone_number;
        $this->logo2 = $configuration->logo;
        $this->valider_photo = $configuration->valider_photo;
        $this->valider_publication = $configuration->valider_publication;
        return view('livewire.informations');
    }



    public function update()
    {
        //validation du formulaire
        $this->validate([
            'email' => ['nullable', 'string'],
            'telephone' => ['nullable', 'string'],
            'tiktok' => ['nullable', 'url'],
            'adresse' => ['nullable', 'string'],
            'facebook' => ['nullable', 'url'],
            'instagram' => ['nullable', 'url'],
            'linkedin' => ['nullable', 'url'],
            'logo'  => 'nullable|image|mimes:jpg,png,jpeg,webp|max:4048',
        ]);


        //verifier si une configuration est deja presente si c'est pas le cas creer une nouvelle
        $old_configuraion = configurations::first();
        if (!$old_configuraion) {
            $old_configuraion = new configurations();
        }

        $config = $old_configuraion::find($old_configuraion->id);
        //check if image is selected
        if ($this->logo) {
            if ($old_configuraion->logo) {
                Storage::disk('public')->delete($old_configuraion->logo);
            }
            $newName = $this->logo->store('uploads/configuration', 'public');
            $config->logo = $newName;
        }

        $config->facebook = $this->facebook;
        $config->instagram = $this->instagram;
        $config->linkedin = $this->linkedin;
        $config->tiktok = $this->tiktok;
        $config->adresse = $this->adresse;
        $config->phone_number   = $this->telephone;
        $config->email = $this->email;
        $config->valider_publication = $this->valider_publication ? true : false;
        $config->valider_photo = $this->valider_photo ? true : false;
        $config->save();

        //show success message
        session()->flash('success', __('Information mises à jour avec succès'));
        $this->dispatch('alert', ['message' => "Information mises à jour avec succès",'type'=>'info']);
    }
}
