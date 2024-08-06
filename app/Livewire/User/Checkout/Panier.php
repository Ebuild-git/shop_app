<?php

namespace App\Livewire\User\Checkout;

use App\Models\posts;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;

class Panier extends Component
{

    public  $success = 0;
    protected $listeners = ['PostAdded' => '$refresh'];
    public $frais  = 25 ;



    public function render()
    {
        $articles_panier = [];
        $total = 0;
        $nbre_article = 0;

        $cart = json_decode($_COOKIE['cart'] ?? '[]', true);

        foreach ($cart as $item) {
            $post = posts::join('sous_categories', 'posts.id_sous_categorie', '=', 'sous_categories.id')
                ->join('categories', 'sous_categories.id_categorie', '=', 'categories.id')
                ->select("categories.pourcentage_gain", "posts.prix", "posts.id_user", "posts.id_sous_categorie", "posts.id",  "posts.titre", "posts.photos", "posts.old_prix")
                ->where("posts.id", $item)
                ->first();
            if ($post) {
                $articles_panier[] = [
                    "id" => $post->id,
                    "titre" => $post->titre,
                    "prix" => $post->getPrix(),
                    "photo" => $post->photos[0],
                    "vendeur" => $post->user_info->username,
                    "is_solder" => $post->old_prix ? true : false,
                    "old_prix" => $post->old_prix
                ];
                $total += round($post->getPrix(), 3);
                $nbre_article++;
            }
        }
        $groupedByVendor = collect($articles_panier)->groupBy('vendeur');
        $uniqueVendorsCount = $groupedByVendor->count();
        $totalDeliveryFees = $uniqueVendorsCount > 0 ? $this->frais * $uniqueVendorsCount : 0;
        $totalWithDelivery = $total + $totalDeliveryFees;

        return view('livewire.user.checkout.panier')
            ->with("articles_panier", $articles_panier)
            ->with("total", $total)
            ->with("cart", $cart)
            ->with("nbre_article", $nbre_article)
            ->with("totalDeliveryFees", $totalDeliveryFees)
            ->with("totalWithDelivery", $totalWithDelivery);
    }



    public function delete($id)
    {
        if (!is_numeric($id) || $id < 0) {
            return;
        }

        $cart = json_decode($_COOKIE['cart'] ?? '[]', true);
        foreach ($cart as $index => $item) {
            if ($item['id'] == $id) {
                unset($cart[$index]);
                break;
            }
        }
        $cart = array_values($cart);



        setcookie('cart', json_encode($cart), time() + (86400 * 30), '/');

        $this->dispatch('PostAdded');
        $this->reset();
    }





    public function vider()
    {
        //empty cart
        setcookie('cart', '[]', time() - 1, '/');
        $this->dispatch('PostAdded');
    }



    public function valider()
    {
        return Redirect("/checkout?step=2");
    }



}
