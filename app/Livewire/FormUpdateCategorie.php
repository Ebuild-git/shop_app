<?php

namespace App\Livewire;

use App\Livewire\Admin\Proprietes;
use App\Models\categories;
use App\Models\proprietes as ModelsProprietes;
use App\Models\regions;
use App\Models\regions_categories;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class FormUpdateCategorie extends Component
{
    use WithFileUploads;
    public $categorie, $titre, $icon, $title_ar, $title_en, $description, $photo, $actu_photo, $id, $pourcentage_gain, $proprietes, $list_regions, $small_icon, $apercu_small_icon, $active;
    public $region_prix = [];

    public function mount($id)
    {
        $this->id = $id;
    }

    public function render()
    {
        $this->region_prix = [];
        $this->categorie = categories::find($this->id);
        $this->titre = $this->categorie->titre;
        $this->title_ar = $this->categorie->title_ar;
        $this->title_en = $this->categorie->title_en;
        $this->icon = $this->categorie->icon;
        $this->pourcentage_gain = $this->categorie->pourcentage_gain ?? 0;
        $this->description = $this->categorie->description;
        $this->actu_photo = $this->categorie->icon;
        $this->apercu_small_icon = $this->categorie->small_icon;
        $this->proprietes = ModelsProprietes::all();
        $this->list_regions = regions::all('id', 'nom');
        $this->active = $this->categorie->active;

        foreach ($this->list_regions as $regi) {
            $data = regions_categories::where("id_categorie", $this->categorie->id)
                ->where("id_region", $regi->id)
                ->select("prix")
                ->first();
            $this->region_prix[] = [
                "id_region" => $regi->id,
                "nom" => $regi->nom,
                "prix" => isset($data->prix) ? number_format($data->prix, 2, '.', '') : null,
            ];
        }

        return view('livewire.form-update-categorie');
    }

    public function modifier()
    {
        // Apply validation
        $this->validate([
            'titre' => ['required', 'string'],
            'title_en' => ['nullable', 'string'],
            'title_ar' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpg,png,jpeg,webp', 'max:10048'],
            'small_icon' => ['nullable', 'image', 'mimes:jpg,png,jpeg,webp,svg', 'max:1048'],
            'pourcentage_gain' => ['numeric', 'nullable', 'min:0'],
            'active' => ['boolean'],
            'region_prix' => ['array'],
            'region_prix.*.prix' => ['nullable', 'numeric'],
        ]);

        try {
            $categorie = categories::findOrFail($this->categorie->id);

            if ($this->photo) {
                Storage::disk('public')->delete($categorie->icon);
                $newName = \App\Services\ImageService::uploadAndConvert($this->photo, 'uploads/categories');
                $categorie->icon = $newName;
            }

            if ($this->small_icon) {
                if ($categorie->small_icon) {
                    Storage::disk('public')->delete($categorie->small_icon);
                }
                $newName = \App\Services\ImageService::uploadAndConvert($this->small_icon, 'uploads/categories');
                $categorie->small_icon = $newName;
            }

            $categorie->titre = $this->titre;
            $categorie->title_en = $this->title_en;
            $categorie->title_ar = $this->title_ar;
            $categorie->description = $this->description ?: '';
            $categorie->pourcentage_gain = $this->pourcentage_gain;
            $categorie->active = $this->active;
            $categorie->save();
            regions_categories::where('id_categorie', $categorie->id)->delete();

            foreach ($this->region_prix as $region) {
                if ($region['prix'] !== null) {
                    $regions_categorie = new regions_categories();
                    $regions_categorie->id_region = $region['id_region'];
                    $regions_categorie->id_categorie = $categorie->id;
                    $regions_categorie->prix = $region['prix'];
                    $regions_categorie->save();
                }
            }
            session()->flash('success', "La catégorie a été modifiée avec succès");
            $this->dispatch('alert', ['message' => "La catégorie a été modifiée avec succès", 'type' => 'success']);
        } catch (\Exception $e) {
            session()->flash('error', 'Une erreur est survenue lors de la modification de la catégorie');
            $this->dispatch('alert', ['message' => "Une erreur est survenue lors de la modification de la catégorie", 'type' => 'info']);

        }
    }


}
