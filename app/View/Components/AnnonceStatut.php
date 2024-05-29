<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AnnonceStatut extends Component
{
    /**
     * Create a new component instance.
     */
    public $statut;
    public function __construct($statut)
    {
        if($statut == "vente"){
            $this->statut = "En vente";
        }else{
            $this->statut = $statut;
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.annonce-statut');
    }
}
