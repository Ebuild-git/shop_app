<?php

namespace App\Livewire\User;

use App\Events\UserEvent;
use App\Models\notifications;
use App\Models\posts;
use App\Models\propositions;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Checkout extends Component
{
    public $cart, $success = 0;
    public function render()
    {
        
        $articles_panier = [];
        $total = 0;
        $nbre_article = 0;

        $this->cart = json_decode($_COOKIE['cart'] ?? '[]', true);

        foreach ($this->cart as $item) {
            $post = posts::join('sous_categories', 'posts.id_sous_categorie', '=', 'sous_categories.id')
                ->join('categories', 'sous_categories.id_categorie', '=', 'categories.id')
                ->select("categories.pourcentage_gain", "posts.prix", "posts.id",  "posts.titre", "posts.photos")
                ->where("posts.id", $item)
                ->first();
            if ($post) {
                $articles_panier[] = [
                    "id" => $post->id,
                    "titre" => $post->titre,
                    "prix" => round($post->prix + (($post->pourcentage_gain * $post->prix) / 100), 3),
                    "photo" => $post->photos[0]
                ];
                $total += round($post->prix + (($post->pourcentage_gain * $post->prix) / 100), 3);
                $nbre_article++;
            }
        }

        return view('livewire.user.checkout')
        ->with("articles_panier",$articles_panier)
        ->with("total",$total)
        ->with("nbre_article",$nbre_article);
    }


    public function delete($id)
    {
        if (!is_numeric($id) || $id < 0) {
            return;
        }


        foreach ($this->cart as $index => $item) {
            if ($item['id'] == $id) {
                unset($this->cart[$index]);
                break;
            }
        }
        $cart = array_values($this->cart);

        setcookie('cart', json_encode($cart), time() + (86400 * 30), '/');

        $this->dispatch('PostAdded');
        $this->reset();
    }












    public function valider()
    {
        $cart = json_decode($_COOKIE['cart'] ?? '[]', true);
        if ($this->total > 0) {
            foreach ($cart as $item) {
                $this->make_proposition($item["id"]);
            }
            session()->flash('success', 'Vos commandes ont été envoyés aux differents vendeurs !');
            session()->flash('info', "Taux de réussite  : " . $this->success . "/" . $this->total);
        } else {
            session()->flash('error', 'Désoler ! votre panier est vide .');
        }
    }











    public function make_proposition($id_post)
    {
        $this->delete($id_post);

        $post = posts::find($id_post);
        if (!$post) {
            return;
        }

        //verifier si cet utilisateur n'a jamais fais de proposition pour ce pots
        $proposition = propositions::where('id_acheteur', Auth::id())->where('id_post', $post->id)->first();
        if ($proposition) {
            return;
        }

        //faire une proposition
        $proposition = new propositions();
        $proposition->id_post = $post->id;
        $proposition->id_acheteur = Auth::user()->id;
        $proposition->id_vendeur = $post->id_user;
        $proposition->save();

        //make notification
        $notification = new notifications();
        $notification->titre = "Une nouvelle commande !";
        $notification->id_user_destination  =  $post->id_user;
        $notification->type = "alerte";
        $notification->message = "Nous vous informons que votre publication  " . $post->titre . " vient de recevoir une nouvelle demande de commande";
        $notification->save();
        event(new UserEvent($post->id_user));
        $this->success++;
    }
}
