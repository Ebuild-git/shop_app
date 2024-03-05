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
            "Tanger-Tétouan-Al Hoceïma",
            "L'Oriental",
            "Fès-Meknès",
            "Rabat-Salé-Kénitra",
            "Béni Mellal-Khénifra",
            "Casablanca-Settat",
            "Marrakech-Safi",
            "Drâa-Tafilalet",
            "Souss-Massa",
            "Guelmim-Oued Noun",
            "Laâyoune-Sakia El Hamra",
            "Dakhla-Oued Ed-Dahab"
        ];
        return $gouvernorats;
    }


    
}
