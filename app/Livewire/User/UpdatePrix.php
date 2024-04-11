<?php

namespace App\Livewire\User;

use App\Models\posts;
use Livewire\Component;

class UpdatePrix extends Component
{
    public $post,$prix;

    public function mount($post){
        $this->post = $post;
    }

    public function render()
    {
        return view('livewire.user.update-prix');
    }

    public function form_update_prix(){
        $post = posts::find($this->post->id);
        if($post){
            $post->prix = $this->prix;
            $post->save();

           // $this->dispatch('update-price');

           //flash message
           session()->flash("success", "Le prix du post a été modifié"); 

        }
    }
}
