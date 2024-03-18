<?php

namespace App\Livewire;

use App\Models\categories;
use App\Models\proprietes;
use App\Models\sous_categories;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ListeCategorieAdmin extends Component
{
    use WithFileUploads;
    public $proprietes, $categories;
    public $proprios = [];
    public $liste;
    protected $listeners = ['categorieCreated' => '$refresh','reorder_do' => 'reorder'];

    public function reorder($data)
    {
        // Convertir la chaîne de caractères en un tableau d'IDs
        $ids = explode(',', $data);
    
        // Traiter chaque ID individuellement
        foreach ($ids as $index => $id) {
            // Votre logique de réorganisation ici...
        }
    }
    


    public function render()
    {
        $this->proprietes = proprietes::all();
        $this->categories = categories::all();
        $this->liste = $this->get_all_categorie();
        return view('livewire.liste-categorie-admin');
    }



    public function get_all_categorie()
    {
        $data = categories::orderBy('order')->get();
        return $data;
    }



    public function delete($id)
    {
        $categorie = categories::findOrFail($id);
        if ($categorie) {
            Storage::disk('public')->delete($categorie->icon);
            $categorie->delete();
        }

        session()->flash("success", "La catégorie a été supprimée avec succès");
        $this->dispatch('categorieCreated');
    }

    public function add_luxury($id)
{
    // Trouver la catégorie correspondante
    $cat = categories::find($id);

    if ($cat) {
        if ($cat->luxury) {
            $cat->luxury = false;
            $cat->save();
        } else {
            categories::where('id', '!=', $id)->update(['luxury' => false]);
            $cat->luxury = true;
            $cat->save();
        }
    }
}


    public function delete_sous_cat($id)
    {
        sous_categories::find($id)->delete();
    }

}
