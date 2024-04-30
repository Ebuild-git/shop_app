<?php

namespace App\Livewire\User;

use App\Models\categories;
use App\Models\posts;
use App\Models\regions;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Shop extends Component
{
    use WithPagination;

    public $gouvernorat, $liste_categories, $key, $categorie, $ordre, $prix_minimun, $prix_maximun, $sous_categorie, $total, $etat,$filtre,$ordre2;



    public function mount($categorie,$key,$sous_categorie){
        $this->categorie = $categorie;
        $this->sous_categorie = $sous_categorie;
        $this->key = $key;
    }

    public function updatedKey($value){
        $this->key = $value;
        $this->resetPage();
    }


    public function updateFiltre($value){
        $this->filtre = $value;
        $this->resetPage();
    }

    public function choix_etat($etat){
        $this->etat = $etat;
        $this->resetPage();
    }

    public function choix_ordre($or){
        if($or == "Asc"){
            $this->ordre2 ="asc";
        }else{
            $this->ordre2 = "desc";
        }
        $this->resetPage();
    }


    public function render(){
        $this->total = posts::count();
        $this->liste_categories = categories::orderBy('order')->get(["titre", "id","luxury"]);
        
        $query = posts::where('statut','vente');

        if (!empty($this->ordre2)) {
            $query->orderBy('prix', ($this->ordre2 == "Desc") ? 'DESC' : 'ASC');
        }
    
        if (!empty($this->ordre)) {
            $query->orderBy('created_at', ($this->ordre == "Desc") ? 'DESC' : 'ASC');
        }


        if (!empty($this->filtre)) {
            $query->orderBy('prix', ($this->filtre == "Desc") ? 'DESC' : 'ASC');
        }
    
        if (!empty($this->gouvernorat)) {
            $query->where('gouvernorat', $this->gouvernorat);
        }
    
        if (!empty($this->key)) {
            $q = $this->key;
            $query->where(function ($query) use ($q) {
                $query->where('titre', 'like', '%' . $q . '%')
                      ->orWhere('proprietes', 'like', '%' . $q . '%')
                      ->orWhere('description', 'like', '%' . $q . '%');
            });

            $query->orWhereHas('sous_categorie_info', function ($query) use ($q) {
                $query->where('titre', 'like', '%' . $q . '%');
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
    
        if (!empty($this->etat)) {
            $query->where('etat', $this->etat);
        }
    
        $regions = regions::all();

        //recuperer toute les regions qui ont un pots identifier par la colonne id_regions dans la table posts



        return view('livewire.user.shop', ['posts' => $query->paginate(30),"regions"=>$regions]);
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


    public function filtre_sous_cat($id){
        $this->sous_categorie = $id;
    }
}
