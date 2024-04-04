<?php

namespace App\Livewire\User;

use App\Models\favoris;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ListFavoris extends Component
{
    public $favoris;
    public function render()
    {
        $this->favoris = favoris::where('id_user', Auth::user()->id)->get();
        return view('livewire.user.list-favoris');
    }


    public function delete($id_post)
    {
        favoris::where('id_post', $id_post)
            ->where('id_user', Auth::user()->id)
            ->delete();
    }
}
