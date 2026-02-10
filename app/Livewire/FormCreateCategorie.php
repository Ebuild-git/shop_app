<?php

namespace App\Livewire;

use App\Models\categories;
use App\Models\proprietes;
use App\Models\regions;
use App\Models\regions_categories;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\Component;

class FormCreateCategorie extends Component
{
    use WithFileUploads;
    public $titre, $description, $photo, $title_ar, $title_en, $pourcentage_gain, $list_regions, $small_icon, $active = true;

    public $regions = [];
    protected $listeners = ['regionCreated' => '$refresh'];

    public function render()
    {
        $this->list_regions = regions::all('id', 'nom');
        return view('livewire.form-create-categorie');
    }

    public function creer()
    {

        $this->validate([
            'titre' => ['required', 'string'],
            'title_en' => ['nullable', 'string'],
            'title_ar' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'photo' => 'required|image|mimes:jpg,png,jpeg,webp|max:10048',
            'pourcentage_gain' => 'numeric|nullable|min:0',
            'small_icon' => 'nullable|image|mimes:jpg,png,jpeg,webp,svg|max:1048',
            'active' => 'boolean',
        ]);

        $newName = \App\Services\ImageService::uploadAndConvert($this->photo, 'uploads/categories');

        $categorie = new categories();
        $categorie->titre = $this->titre;
        $categorie->title_en = $this->title_en;
        $categorie->title_ar = $this->title_ar;
        $categorie->description = $this->description;
        $categorie->icon = $newName;
        $categorie->pourcentage_gain = $this->pourcentage_gain ?? 0;
        $categorie->active = $this->active;
        if ($this->small_icon) {
            $categorie->small_icon = \App\Services\ImageService::uploadAndConvert($this->small_icon, 'uploads/categories');
        }

        if ($categorie->save()) {
            foreach ($this->regions as $cle => $valeur) {
                $regions_categorie = new regions_categories();
                $regions_categorie->id_region = $cle;
                $regions_categorie->id_categorie = $categorie->id;
                $regions_categorie->prix = $valeur;
                $regions_categorie->save();
            }
            $this->reset(['titre', 'description', 'photo', 'title_en', 'title_ar']);
            session()->flash("success", "La catégorie a été ajoutée avec succès");
            $this->dispatch('alert', ['message' => "La catégorie a été ajoutée avec succès", 'type' => 'success']);
            $this->list_regions = "";
            $this->dispatch('categorieCreated');
        } else {
            session()->flash("error", "Une erreur est survenue lors de l'ajout de la catégorie. Veuillez réessayer plus tard.");
            $this->dispatch('alert', ['message' => "Une erreur est survenue !", 'type' => 'info']);
        }
    }
}
