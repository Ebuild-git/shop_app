<?php

namespace App\Livewire\User;

use Livewire\Component;

class Panier extends Component
{
    public $cart, $total;
    protected $listeners = ['PostAdded' => 'get'];



    public function render()
    {
        $this->get();
        return view('livewire.user.panier');
    }

    public function delete($id)
    {
        // Validation de l'ID
        if (!is_numeric($id) || $id < 0) {
            return; // Arrêtez-vous si l'ID n'est pas valide
        }

        // Récupérer le contenu actuel du panier depuis le cookie
        $cart = json_decode($_COOKIE['cart'] ?? '[]', true);

        // Recherche de l'élément correspondant à l'ID dans le panier et le supprimer
        foreach ($cart as $index => $item) {
            if ($item['id'] == $id) {
                unset($cart[$index]);
                break; // Arrêtez-vous une fois que l'élément est trouvé et supprimé
            }
        }

        // Ré-indexage du tableau du panier
        $cart = array_values($cart);

        // Mise à jour du cookie du panier avec le contenu mis à jour
        setcookie('cart', json_encode($cart), time() + (86400 * 30), '/');

        // Rafraîchissement des données du composant et de la vue
        $this->get();
        $this->dispatch('PostAdded');
    }


    public function get()
    {
        $this->cart = json_decode($_COOKIE['cart'] ?? '[]', true);

    }
}
