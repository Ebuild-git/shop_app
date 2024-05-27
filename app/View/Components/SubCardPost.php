<?php

namespace App\View\Components;

use App\Models\posts;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SubCardPost extends Component
{
    /**
     * Create a new component instance.
     */
    public $id_post,$post,$show;
    public function __construct($idPost)
    {
        $this->show=false;
        $this->id_post = $idPost;
        $post = posts::find( $idPost);
        if($post){
            $this->post = $post;
            $this->show=true;
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sub-card-post');
    }
}
