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
    public function render()
    {
        $this->liste_gouvernorat = $this->get_list_gouvernorat();
        $this->total = posts::count();
        $this->liste_categories = categories::all(["titre", "id", "icon"]);
        $Query = posts::where("verified_at", '!=', null);
        if (!empty($this->ordre)) {
            if ($this->ordre == "Desc") {
                $Query = $Query->orderBy('created_at', 'DESC');
            } else {
                $Query = $Query->orderBy('created_at');
            }
        }

        if (!empty($this->gouvernorat)) {
            $Query->where('gouvernorat', $this->gouvernorat);
        }

        if (!empty($this->categorie)) {
            $Query->where('id_categorie', $this->categorie);
        }
        if (!empty($this->prix_minimun)) {
            $Query->where('prix', '>=', $this->prix_minimun);
        }
        if (!empty($this->prix_maximun)) {
            $Query->where('prix', '<=', $this->prix_maximun);
        }
        if (!empty($this->sous_categorie)) {
            $Query->where('id_sous_categorie', $this->sous_categorie);
        }

        if ($this->etat == "neuf" || $this->etat == "occasion") {
            $Query->where('etat', $this->etat);
        }

        //recherche en fonction du mot cle
        if (!empty($this->key)) {
            $q = $this->key;
            $Query = $Query->where(function ($query) use ($q) {
                $query->where('titre', 'like', '%' . $q . '%')
                    ->OrWhere('description', 'like', '%' . $q . '%');
            });
        }
        return view('livewire.user.shop', ['posts' => $Query->paginate(30)]);
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
