<?php

namespace App\Livewire\User\Checkout;

use App\Models\posts;
use App\Models\UserCart;
use App\Models\sous_categories;
use App\Models\regions_categories;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Panier extends Component
{
    public $success = 0;
    protected $listeners = ['PostAdded' => '$refresh'];
    public $frais  = 0;

    public function render()
    {
        $articles_panier = [];
        $total = 0;
        $nbre_article = 0;
        $user_id = Auth::id();
        $cartItems = UserCart::where('user_id', $user_id)->pluck('post_id');

        foreach ($cartItems as $item) {
            $post = posts::join('sous_categories', 'posts.id_sous_categorie', '=', 'sous_categories.id')
                ->join('categories', 'sous_categories.id_categorie', '=', 'categories.id')
                ->select("categories.pourcentage_gain", "posts.prix", "posts.id_user", "posts.id_sous_categorie", "posts.id",  "posts.titre", "posts.photos", "posts.old_prix")
                ->where("posts.id", $item)
                ->first();

            if ($post) {
                $id_categorie = $post->id_sous_categorie
                ? sous_categories::where('id', $post->id_sous_categorie)->value('id_categorie')
                : null;

                $id_region = Auth::user()->region ?? null;

                $fraisLivraison = '0';
                if ($id_categorie && $id_region) {
                    $regionCategory = regions_categories::where('id_region', $id_region)
                        ->where('id_categorie', $id_categorie)
                        ->first();
                        $fraisLivraison = $regionCategory ? number_format($regionCategory->prix, 2, ',', '') : '0,00';

                }
                $articles_panier[] = [
                    "id" => $post->id,
                    "titre" => $post->titre,
                    "prix" => $post->getPrix(),
                    "photo" => $post->photos[0],
                    "vendeur" => $post->user_info->username,
                    "is_solder" => $post->old_prix ? true : false,
                    "old_prix" => $post->getOldPrix(),
                    "frais" => $fraisLivraison,
                ];
                $total += round($post->getPrix(), 3);
                $nbre_article++;
            }

        }

        $groupedByVendor = collect($articles_panier)->groupBy('vendeur');
        $uniqueVendorsCount = $groupedByVendor->count();
        $totalDeliveryFees = $groupedByVendor->map(function ($items) {
            return (float) $items->first()['frais'];
        })->sum();

        $totalWithDelivery = $total + $totalDeliveryFees;
        return view('livewire.user.checkout.panier')
            ->with("articles_panier", $articles_panier)
            ->with("total", $total)
            ->with("nbre_article", $nbre_article)
            ->with("totalDeliveryFees", $totalDeliveryFees)
            ->with("totalWithDelivery", $totalWithDelivery);
    }

    public function delete($id)
    {
        if (!is_numeric($id) || $id < 0) {
            return;
        }

        $user_id = Auth::id();
        UserCart::where('user_id', $user_id)->where('post_id', $id)->delete();

        $this->dispatch('PostAdded');
        $this->reset();
    }

    public function vider()
    {
        // Empty cart
        $user_id = Auth::id();
        UserCart::where('user_id', $user_id)->delete();

        $this->dispatch('PostAdded');
    }

    public function valider()
    {
        return Redirect("/checkout?step=2");
    }

}
