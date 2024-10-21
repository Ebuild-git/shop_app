<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class Etoiles extends Component
{
    /**
     * Create a new component instance.
     */
    public $count,$avis, $user;

    public function __construct($count,$avis, $user = null)
    {
        $this->count = $count;
        $this->avis = $avis;
        $this->user = $user ?? Auth::user();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.etoiles');
    }
}
