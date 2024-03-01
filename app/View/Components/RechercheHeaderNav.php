<?php

namespace App\View\Components;

use App\Models\sous_categories;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RechercheHeaderNav extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {


        $topSubcategories = sous_categories::withCount('getPost')
            ->take(3)
            ->get();

        return view('components.recherche-header-nav', compact("topSubcategories"));
    }
}
