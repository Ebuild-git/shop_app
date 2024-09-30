<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ModeToggle extends Component
{

    public $isVoyageMode;

    public function mount()
    {
        $this->isVoyageMode = Auth::user()->voyage_mode;
    }

    public function toggleVoyageMode()
    {
        $this->isVoyageMode = !$this->isVoyageMode;
        $user = Auth::user();
        $user->voyage_mode = $this->isVoyageMode;
        $user->save();

        if ($this->isVoyageMode) {
            $this->dispatch('alert', ['message' => "Vous êtes maintenant en mode Voyage.", 'type' => 'info']);
        } else {
            $this->dispatch('alert', ['message' => "Vous avez quitté le mode Voyage.", 'type' => 'info']);
        }

    }

    public function render()
    {
        return view('livewire..user.mode-toggle');
    }
}
