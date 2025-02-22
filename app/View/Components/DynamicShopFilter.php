<?php

namespace App\View\Components;

use App\Models\sous_categories;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Traits\ListColor;
use Illuminate\Support\Str;

class DynamicShopFilter extends Component
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
            "fashion & vêtements" => ["Etat", "Prix", "Article pour", "Taille", "Couleur"],
            "sacs & accessoires" => ["Etat", "Prix", "Article pour", "Couleur", "Matière"],
            "chaussures" => ["État", "Prix", "Article pour", "Pointure", "Couleur", "Matière de chaussures"],
            "livres & fournitures" => ["État", "Prix", "Langue du livre"],
            "langue du livre" => ["État", "Prix"],
            "jouets & divertissements"=> ["État","Prix"],
            "univers bébé" => ["Taille","État","Prix", "Pointure"],
            "univers sport" => ["État","Prix"],
            "univers maison" => ["État","Prix"],
            "univers bébé & enfants" => ["État", "Prix", "Pointure Bébé", "Taille Bébé", "Couleur", "Matière de chaussures", "Pointure Enfant", "Taille Enfant"],
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
        return view('components.dynamic-shop-filter');
    }
}
