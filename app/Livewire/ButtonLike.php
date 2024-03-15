<?php

namespace App\Livewire;

use App\Models\likes;
use App\Models\posts;
use Livewire\Component;

class ButtonLike extends Component
{
    public $count, $id_post;
    public $liked = false;


    public function mount($id_post)
    {
        $this->count = $id_post;
    }

    public function render()
    {
        $post = posts::find($this->id_post);
        if ($post) {
            if ($post->getLike->where('id_user', auth()->user()->id)->exists()) {
                $this->liked = true;
            }
        }
        return view('livewire.button-like');
    }

    public function like()
    {
        if ($this->liked !== false) {
            likes::firstOrCreate(
                [
                    "id_post" => $this->post->id,
                    "id_user" => auth()->user()->id
                ]
            );
        }
    }



}
