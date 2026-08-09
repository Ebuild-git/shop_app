<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Services\VoyageModeAlertService;

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

    public function toggleVoyageMode(VoyageModeAlertService $voyageModeAlertService)
    {
        $this->isVoyageMode = !$this->isVoyageMode;
        $user = Auth::user();
        $user->voyage_mode = $this->isVoyageMode;
        $user->save();

        if ($this->isVoyageMode) {
            $voyageModeAlertService->handleVoyageModeActivated($user);
            $this->dispatch('voyage-mode-activated');
        } else {
            $voyageModeAlertService->handleVoyageModeDeactivated($user);
            $this->dispatch('voyage-mode-deactivated');
        }
    }

    public function render()
    {
        return view('livewire..user.mode-toggle');
    }
}
