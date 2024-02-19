<?php

namespace App\Livewire;

use App\Models\categories;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class FormUpdateCategorie extends Component
{
    use WithFileUploads;
    public $categorie, $titre, $description,$photo,$id;

    public function mount($id)
    {
        $this->id = $id;
    }


    public function render()
    {
        $this->categorie = categories::find($this->id);
        $this->titre = $this->categorie->titre;
        $this->description = $this->categorie->description;
        return view('livewire.form-update-categorie');
    }

    public function modifier()
    {
        try {
            $categorie = categories::findOrFail($this->categorie->id);
            if ($this->photo) {
                Storage::disk('public')->delete($categorie->icon);
                $newName = $this->photo->store('uploads/categories', 'public');
                $categorie->icon = $newName;
            }
            $categorie->titre = $this->titre;
            $categorie->description = $this->description;
            $categorie->save();
            session()->flash('success-modal', "La catégorie a été modifiée avec succès");
        } catch (\Exception $e) {
            session()->flash('error-modal', 'Une erreur est survenue lors de la modification de la catégorie');
        }
    }
}
