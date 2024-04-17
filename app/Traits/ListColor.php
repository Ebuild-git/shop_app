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
        // Les couleurs et leurs codes correspondants
        $colors = [
            ["nom" => "Argenté", "code" => "#C0C0C0"],
            ["nom" => "Beige", "code" => "#F5F5DC"],
            ["nom" => "Blanc", "code" => "#FFFFFF"],
            ["nom" => "Bleu", "code" => "#0000FF"],
            ["nom" => "Bleu-vert", "code" => "#008080"],
            ["nom" => "Bordeaux", "code" => "#800000"],
            ["nom" => "Camel", "code" => "#C19A6B"],
            ["nom" => "Corail", "code" => "#FF7F50"],
            ["nom" => "Doré", "code" => "#FFD700"],
            ["nom" => "Fushia", "code" => "#FF00FF"],
            ["nom" => "Gris", "code" => "#808080"],
            ["nom" => "Jaune", "code" => "#FFFF00"],
            ["nom" => "Marron", "code" => "#800000"],
            ["nom" => "Noir", "code" => "#000000"],
            ["nom" => "Nude", "code" => "#F5DEB3"],
            ["nom" => "Orange", "code" => "#FFA500"],
            ["nom" => "Rose", "code" => "#FFC0CB"],
            ["nom" => "Rouge", "code" => "#FF0000"],
            ["nom" => "Turquoise", "code" => "#40E0D0"],
            ["nom" => "Taupe", "code" => "#483C32"],
            ["nom" => "Vert", "code" => "#008000"],
            ["nom" => "Violet", "code" => "#800080"],
            ["nom" => "Multicolore", "code" => "#000000"],
        ];

        return collect($colors);
    }
}
