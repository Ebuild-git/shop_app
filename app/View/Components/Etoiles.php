<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Etoiles extends Component
{
    /**
     * Create a new component instance.
     */
    public $count,$avis;
    public function __construct($count,$avis)
    {
        $this->count = $count;
        $this->avis = $avis;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.etoiles');
    }
}
