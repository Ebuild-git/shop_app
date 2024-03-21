<?php

namespace App\Livewire\User;

use App\Models\posts;
use Livewire\Component;

class ButtonAddPanier extends Component
{
    public $post;

    public function mount($id_post)
    {
        $post = posts::where("id", $id_post)->where("statut", "vente")->first();
        if ($post) {
            $this->post = $post;
        }
    }

    public function render()
    {
        return view('livewire.user.button-add-panier');
    }

    public function add()
    {
        $cart = json_decode($_COOKIE['cart'] ?? '[]', true);

        $productExists = false;
        foreach ($cart as $item) {
            if ($item['id'] == $this->post->id) {
                $productExists = true;
                break;
            }
        }

        if (!$productExists) {
            $cart[] = [
                'id' => $this->post->id,
            ];
            setcookie('cart', json_encode($cart), time() + (86400 * 30), '/');

            $this->dispatch('PostAdded');
        }
    }
    

}
