<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait ListGouvernorat
{

    /**
     * @param Request $request
     * @return $this|false|string
     */
    public function get_list_gouvernorat()
    {
        $gouvernorats = [
            "Ariana",
            "Béja",
            "Ben Arous",
            "Bizerte",
            "Gabès",
            "Gafsa",
            "Jendouba",
            "Kairouan",
            "Kasserine",
            "Kébili",
            "Le Kef",
            "Mahdia",
            "La Manouba",
            "Médenine",
            "Monastir",
            "Nabeul",
            "Sfax",
            "Sidi Bouzid",
            "Siliana",
            "Sousse",
            "Tataouine",
            "Tozeur",
            "Tunis",
            "Zaghouan"
        ];
        return $gouvernorats;
    }


    
}
