<?php

namespace App\View\Components;

use App\Models\sous_categories;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Traits\ListColor;
use Illuminate\Support\Str;

class DynamicShopFilterMobile extends Component
{
    use ListColor;
    /**
     * Create a new component instance.
     */
    public $selected_sous_categorie, $show_option, $colors;
    public function __construct($idsouscategorie)
    {
        $sous_categorie = sous_categories::find($idsouscategorie);
        $this->colors = $this->get_list_color();
        $categorie = $sous_categorie->categorie;

        $this->selected_sous_categorie = $sous_categorie;

        //pas de majuscule dans les nom des key
        $regles = [
            "fashion & vêtements" => ["Etat", "Prix", "Article pour", "Taille", "Taille en chiffre", "Taille en chiffre", "Couleur"],
            "sacs & accessoires" => ["Etat", "Prix", "Article pour", "Couleur"],
            "chaussures" => ["État", "Prix", "Article pour", "Pointure", "Couleur"],
            "livres & fournitures" => ["État", "Prix", "Langue du livre"],
            "langue du livre" => ["État", "Prix"],
            "jouets & divertissements"=> ["État","Prix"],
            "univers bébé" => ["Taille - bébé","Pointure - Bébé","État","Prix"],
            "univers sport" => ["État","Prix"],
            "univers maison" => ["État","Prix"],
        ];



        $result = $regles[Str::lower($categorie->titre)] ?? [];
        if ($result) {
            $this->show_option = array_map('strtolower', $result);
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dynamic-shop-filter-mobile');
    }
}
