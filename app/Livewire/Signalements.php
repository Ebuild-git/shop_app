<?php

namespace App\Livewire;

use App\Models\posts;
use Livewire\Component;
use Livewire\WithPagination;
class Signalements extends Component
{
    use WithPagination;

    public function render()
    {
        $posts = posts::withCount('signalements')->has('signalements')->paginate(50);
        return view('livewire.signalements', compact('posts'));
    }
}
