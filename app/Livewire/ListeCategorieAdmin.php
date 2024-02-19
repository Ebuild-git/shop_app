<?php

namespace App\Livewire;

use App\Models\categories;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ListeCategorieAdmin extends Component
{
    public $liste;
    protected $listeners = ['categorieCreated' => '$refresh'];

    public function render()
    {
        $this->liste = $this->get_all_catgorie();
        return view('livewire.liste-categorie-admin');
    }

    public function get_all_catgorie(){
        $data = categories::Orderby("id","Desc")->get();
        return $data;
    }

    public function delete($id){
        $categorie = categories::findOrFail($id);
        if($categorie){
           Storage::disk('public')->delete($categorie->icon);
            $categorie->delete();
        }
        
       // session()->flash("message", "La catégorie a été supprimée avec succès");
    }
    
}
