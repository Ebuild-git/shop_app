<?php

namespace App\Livewire;

use App\Models\categories;
use Livewire\WithFileUploads;
use Livewire\Component;

class FormCreateCategorie extends Component
{
    use WithFileUploads;
    public $titre, $description, $photo;

    public function render()
    {
        return view('livewire.form-create-categorie');
    }

    public function creer()
    {
        $this->validate([
            'titre' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'photo' => 'required|image|mimes:jpg,png,jpeg,webp|max:2048'
        ]);

        if ($this->photo) {
            $newName = $this->photo->store('uploads/categories', 'public');

            $categorie = new categories(); // Assurez-vous que le nom de la classe modèle est correct
            $categorie->titre = $this->titre;
            $categorie->description = $this->description;
            $categorie->icon = $newName; // Assurez-vous que le champ de la base de données est correctement nommé
            $categorie->save();

            session()->flash("success", "La catégorie a été ajoutée avec succès");
            $this->reset(['titre', 'description', 'photo']);
            $this->dispatch('categorieCreated'); // Utilisez emit() au lieu de dispatch() pour les événements Livewire
        } else {
            session()->flash("error", "Une erreur est survenue lors de l'importation de l'image !");
        }
    }
}
