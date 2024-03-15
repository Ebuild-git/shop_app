<?php

namespace App\Livewire\User;

use Livewire\Component;

class CountPanier extends Component
{

    public $total;
    protected $listeners = ['PostAdded' => '$refresh'];


    public function render()
    {
        $cart = json_decode($_COOKIE['cart'] ?? '[]', true);
        $this->total = count($cart);
        return view('livewire.user.count-panier');
    }
}
