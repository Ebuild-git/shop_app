<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CardPost extends Component
{
    /**
     * Create a new component instance.
     */
    public $post,$col;
    public function __construct($post,$col)
    {
        $this->post=$post;
        $this->col=$col;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.card-post');
    }
}
