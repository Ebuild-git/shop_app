<?php

namespace App\Livewire\User;

use App\Models\categories;
use App\Models\posts;
use App\Models\proprietes;
use App\Models\regions;
use App\Models\sous_categories;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Shop extends Component
{
    use WithPagination;

    public $gouvernorat, $liste_categories, $key, $categorie, $ordre, $prix_minimun, $prix_maximun, $sous_categorie, $etat, $filtre, $ordre2;
    public $luxury_only = false;
    public $proprietes_sous_cat = [];
    public $key2;

    public function mount($categorie, $key, $sous_categorie, $luxury_only)
    {
        if ($luxury_only == "true") {
            $this->luxury_only = true;
        }
        $this->categorie = $categorie;
        $this->sous_categorie = $sous_categorie;
        $this->key = $key;
    }

    public function updatedKey($value)
    {
        $this->key = $value;
        $this->resetPage();
    }

    public function set_key($value)
    {
        $this->key2 = $value;
        $this->resetPage();
    }

    public function updateFiltre($value)
    {
        $this->filtre = $value;
        $this->resetPage();
    }

    public function choix_etat($etat)
    {
        $this->etat = $etat;
        $this->resetPage();
    }

    public function choix_ordre($or)
    {
        if ($or == "Asc") {
            $this->ordre2 = "asc";
        } else {
            $this->ordre2 = "desc";
        }
        $this->resetPage();
    }

    public function check_luxury_only()
    {
        if ($this->luxury_only) {
            $this->luxury_only = false;
        } else {
            $this->luxury_only = true;
        }

        $this->resetPage();
    }

    //faire le filtre des posts pour une categorie procise
    public function filtre_cat($id_categorie)
    {
        $this->categorie = $id_categorie;
        $this->resetPage();
    }

    //filtre des element des sous categories
    public function filtre_sous_cat($id)
    {
        $this->sous_categorie = $id;
        $sous_cat = sous_categories::select("proprietes")->find($id);
        if ($sous_cat) {
            $Array = [];
            foreach ($sous_cat->proprietes as $propriete) {
                $proprietes = proprietes::select("options", "nom")->find($propriete);
                if ($proprietes) {
                    $optionsArray = [];
                    foreach ($proprietes->options ?? [] as $pro) {
                        $optionsArray[] = [
                            "nom" => $pro
                        ];
                    }
                    if (!empty($optionsArray)) {
                        $Array[] = [
                            "nom" => $proprietes->nom,
                            "options" => $optionsArray
                        ];
                    }
                }
            }
            $this->proprietes_sous_cat = $Array;
        }
    }



    public function render()
    {
        $total = posts::whereNotNull('verified_at')->count();
        $this->liste_categories = categories::orderBy('order')->get(["titre", "id", "luxury"]);

        $query = posts::whereNotNull('verified_at')->where('statut', 'vente');

        if (!empty($this->ordre2)) {
            $query->orderBy('prix', ($this->ordre2 == "Desc") ? 'DESC' : 'ASC');
        }

        if ($this->luxury_only) {
            //sachant que le post est lier a une sous categorie qui est lier a une categoerie, je veux tout les post donc la categorie est luxuryt true
            $query->whereHas('sous_categorie_info.categorie', function ($q) {
                $q->where('luxury', true);
            });
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
            $q = strtolower($this->key); // Convertir la recherche en minuscules

            $query->where(function ($query) use ($q) {
                $query->whereRaw('LOWER(titre) LIKE ?', ['%' . $q . '%']) // Recherche insensible à la casse sur la colonne 'titre'
                    ->orWhereRaw('LOWER(proprietes) LIKE ?', ['%' . $q . '%']) // Recherche insensible à la casse sur la colonne 'proprietes'
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . $q . '%']); // Recherche insensible à la casse sur la colonne 'description'
            });

            $query->orWhereHas('sous_categorie_info', function ($query) use ($q) {
                $query->whereRaw('LOWER(titre) LIKE ?', ['%' . $q . '%']); // Recherche insensible à la casse sur la relation 'sous_categorie_info'
            });
        }

        if (!empty($this->categorie)) {
            $query->whereHas('sous_categorie_info.categorie', function ($query) {
                $query->where('id', $this->categorie);
            });
        }

        if (!empty($this->sous_categorie)) {
            //si on fais une recherche internet danbs une sous actegorie pour les proprietes du produits
            if (!empty($this->key2)) {
                $u = $this->key2;
                $query->where(function ($query) use ($u) {
                    $query->WhereRaw('proprietes LIKE ?', ['%' . $u . '%']);
                });
            }
            $query->where('id_sous_categorie', $this->sous_categorie);
        }

        if (!empty($this->etat)) {
            $query->where('etat', $this->etat);
        }

        $regions = regions::all();

        //recuperer toute les regions qui ont un pots identifier par la colonne id_regions dans la table posts



        return view('livewire.user.shop', ['posts' => $query->paginate(30), "regions" => $regions, "total"=>$total]);
    }



    public function filtrer()
    {
        $this->resetPage();
    }



    public function reset_form()
    {
        return redirect()->route("shop");
    }
}
