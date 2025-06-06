<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatutLivraison extends Component
{
    /**
     * Create a new component instance.
     */
    public $statut;
    public function __construct($statut)
    {
        if($statut == "vendu"){
            $this->statut = __('preparation');
        }elseif($statut == "livraison"){
            $this->statut = __('in_progress_delivery');
        }else{
            $this->statut = $statut;
        }

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.statut-livraison');
    }
}
