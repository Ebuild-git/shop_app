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
            $post->old_prix = $post->prix;
            $post->prix = $this->prix;
            $post->save();

           // $this->dispatch('update-price');

           return redirect()->route('details_post_single',['id' => $post->id]);

        }
    }
}
