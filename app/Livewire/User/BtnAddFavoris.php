<?php

namespace App\Livewire\User;

use App\Models\favoris;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BtnAddFavoris extends Component
{
    public $id_post;
    public $liked = false;

    public function mount($id_post)
    {
        $this->id_post = $id_post;
    }

    public function render()
    {
        $this->liked = favoris::where("id_post", $this->id_post)
                ->where('id_user', Auth::user()->id)
                ->exists();
        return view('livewire.user.btn-add-favoris');
    }


    public function add_favoris()
    {
        if (Auth::check()) {
            if ($this->liked === true) {
                favoris::where("id_post", $this->id_post)
                    ->where('id_user', Auth::user()->id)
                    ->delete();
            } else {
                favoris::firstOrCreate(
                    [
                        'id_post' => $this->id_post,
                        'id_user' => Auth::user()->id,
                        'created_at' => now(),
                    ]
                );
            }
        }
    }
}
