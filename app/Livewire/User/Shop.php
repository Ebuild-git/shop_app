<?php

namespace App\Livewire\User;

use App\Models\categories;
use App\Models\posts;
use Livewire\Component;
use App\Traits\ListGouvernorat;
use Livewire\WithPagination;

class Shop extends Component
{
    use ListGouvernorat;
    use WithPagination;

    public $liste_gouvernorat,$gouvernorat, $liste_categories, $key, $categorie, $ordre, $prix_minimun, $prix_maximun, $sous_categorie, $total, $etat;


    public function mount($categorie,$key){
        $this->categorie = $categorie;
        $this->key = $key;
    }

    public function updatedKey($value){
        $this->key = $value;
        $this->resetPage();
    }

    public function render()
    {
        $this->liste_gouvernorat = $this->get_list_gouvernorat();
        $this->total = posts::count();
        $this->liste_categories = categories::all(["titre", "id"]);
        
        $query = posts::whereNotNull('verified_at')->whereNull('sell_at');
    
        if (!empty($this->ordre)) {
            $query->orderBy('created_at', ($this->ordre == "Desc") ? 'DESC' : 'ASC');
        }
    
        if (!empty($this->gouvernorat)) {
            $query->where('gouvernorat', $this->gouvernorat);
        }
    
        if (!empty($this->key)) {
            $q = $this->key;
            $query->where(function ($query) use ($q) {
                $query->where('titre', 'like', '%' . $q . '%')
                      ->orWhere('description', 'like', '%' . $q . '%');
            });
        }
    
        if (!empty($this->categorie)) {
            $query->whereHas('sous_categorie_info.categorie', function ($query) {
                $query->where('id', $this->categorie);
            });
        }
    
        if (!empty($this->sous_categorie)) {
            $query->where('id_sous_categorie', $this->sous_categorie);
        }
    
        if ($this->etat == "neuf" || $this->etat == "occasion") {
            $query->where('etat', $this->etat);
        }
    
        return view('livewire.user.shop', ['posts' => $query->paginate(30)]);
    }
    


    public function filtrer()
    {
        $this->resetPage();
    }

    public function reset_form()
    {
        $this->categorie = null;
        $this->sous_categorie = null;
        $this->prix_minimun = null;
        $this->prix_maximun = null;
        $this->key = "";
        $this->resetPage();
    }
}
