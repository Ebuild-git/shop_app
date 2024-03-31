<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait ListColor
{

    /**
     * @param Request $request
     * @return $this|false|string
     */
    public function get_list_color()
    {
        $colors = [
            "Argenté" => "#C0C0C0",
            "Beige" => "#F5F5DC",
            "Blanc" => "#FFFFFF",
            "Bleu" => "#0000FF",
            "Bleu-vert" => "#008080",
            "Bordeaux" => "#800000",
            "Camel" => "#C19A6B",
            "Corail" => "#FF7F50",
            "Doré" => "#FFD700",
            "Fushia" => "#FF00FF",
            "Gris" => "#808080",
            "Jaune" => "#FFFF00",
            "Marron" => "#800000",
            "Noir" => "#000000",
            "Nude" => "#F5DEB3",
            "Orange" => "#FFA500",
            "Rose" => "#FFC0CB",
            "Rouge" => "#FF0000",
            "Turquoise" => "#40E0D0",
            "Taupe" => "#483C32",
            "Vert" => "#008000",
            "Violet" => "#800080",
            "Multicolore" => "#000000", 
        ];
        return $colors;
    }


    
}
