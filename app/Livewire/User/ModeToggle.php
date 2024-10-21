<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ModeToggle extends Component
{

    public $isVoyageMode;

    public function mount()
    {
        if (Auth::check()) {
            $this->isVoyageMode = Auth::user()->voyage_mode;
        } else {
            $this->isVoyageMode = false;
        }
    }

    public function toggleVoyageMode()
    {
        $this->isVoyageMode = !$this->isVoyageMode;
        $user = Auth::user();
        $user->voyage_mode = $this->isVoyageMode;
        $user->save();

        if ($this->isVoyageMode) {
            $this->dispatch('voyage-mode-activated');
        } else {
            $this->dispatch('voyage-mode-deactivated');
        }
    }
    public function render()
    {
        return view('livewire..user.mode-toggle');
    }
}
