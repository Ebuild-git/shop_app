<?php

namespace App\Livewire\User;

use App\Models\posts;
use Livewire\Component;

class ProductViewModal extends Component
{

    public $id_post;

    public function mount($id_post){
        $this->id_post = $id_post;
    }

    public function render()
    {
        $post = posts::find($this->id_post);
        return view('livewire.user.product-view-modal', compact('post'));
    }
}
