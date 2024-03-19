<?php

namespace App\Livewire\User;

use App\Models\likes;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ButtonAddLike extends Component
{

    public $id_post, $total;
    public $liked = false;
    public function render()
    {
        $this->total = likes::where("id_post", $this->id_post)->count();
        if (Auth::check()) {
            $this->liked = likes::where("id_post", $this->id_post)
                ->where('id_user', Auth::user()->id)
                ->exists();
        }
        return view('livewire.user.button-add-like');
    }


    public function like()
    {
        if (Auth::check()) {
            if ($this->liked === true) {
                likes::where("id_post", $this->id_post)
                    ->where('id_user', Auth::user()->id)
                    ->delete();
            } else {
                likes::firstOrCreate(
                    [
                        'id_post' => $this->id_post,
                        'id_user' => Auth::user()->id
                    ]
                );
            }
        }
    }
}
