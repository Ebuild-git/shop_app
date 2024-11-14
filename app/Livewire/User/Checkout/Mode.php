<?php

namespace App\Livewire\User\Checkout;

use App\Mail\commande;
use App\Models\posts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\UserCart;
use App\Models\User;
use App\Models\notifications;
use App\Events\UserEvent;
use Illuminate\Support\Facades\DB;
use App\Mail\VenteConfirmee;
use Livewire\Component;

class Mode extends Component
{
    public $user,$articles_panier;
    public $frais  = 25 ;



    public function mount(){
        $this->user = Auth::user();
         $secondAddress = $this->user->addresses()->where('is_default', true)->first();

        if ($secondAddress) {
        $this->user->address = $secondAddress->city;
        $this->user->rue = $secondAddress->street;
        $this->user->nom_batiment = $secondAddress->building_name;
        $this->user->etage = $secondAddress->floor;
        $this->user->num_appartement = $secondAddress->apartment_number;
        $this->user->phone_number = $secondAddress->phone_number;

        $region = $secondAddress->regionExtra;

        if ($region) {
            $this->user->region_info = $region;
        }
        }
    }


    public function render()
    {
        $total = 0;
        $nbre_article = 0;
        $this->articles_panier = [];
        $user_id = auth()->id();

        // Get cart items from UserCart based on user_id
        $cartItems = UserCart::where('user_id', $user_id)->pluck('post_id');

        foreach ($cartItems as $item_id) {
            $post = posts::join('sous_categories', 'posts.id_sous_categorie', '=', 'sous_categories.id')
                ->join('categories', 'sous_categories.id_categorie', '=', 'categories.id')
                ->select("categories.pourcentage_gain", "posts.prix", "posts.id_user", "posts.id_sous_categorie", "posts.id",  "posts.titre", "posts.photos", "posts.old_prix")
                ->where("posts.id", $item_id)
                ->first();

            if ($post) {
                $this->articles_panier[] = [
                    "id" => $post->id,
                    "titre" => $post->titre,
                    "prix" => $post->getPrix(),
                    "photo" => config('app.url').Storage::url($post->photos[0]),
                    "vendeur" => $post->user_info->username,
                    "is_solder" => $post->old_prix ? true : false,
                    "old_prix" => $post->old_prix
                ];
                $total += round($post->getPrix(), 3);
                $nbre_article++;
            }
        }

        return view('livewire.user.checkout.mode')
            ->with("total", $total)
            ->with("nbre_article", $nbre_article);
    }

    public function confirm()
    {
        // Loop through each product in the cart
        foreach ($this->articles_panier as $article) {
            $post = posts::find($article['id']);
            if ($post) {
                // Update the post's status to 'sold' and set the buyer's ID
                $post->update(
                    [
                        'statut' => 'vendu',
                        'sell_at' => now(),
                        'id_user_buy' => $this->user->id
                    ]
                );

                 // Fetch the seller's user model
                $seller = User::find($post->id_user);

                // Determine the salutation based on gender
                $salutation = 'Cher';
                if ($seller) {
                    $gender = $seller->gender;
                    if ($gender === 'female') {
                        $salutation = 'Chère';
                    }
                }

                // Retrieve the buyer's username
                $buyerPseudo = Auth::user()->username;

                if ($seller) {
                    Mail::to($seller->email)->send(new VenteConfirmee($seller, $post, $buyerPseudo));
                }
                $notification = new notifications();
                $notification->titre = "Une nouvelle commande !";
                $notification->id_user_destination = $post->id_user;
                $notification->type = "alerte";
                $notification->url = "/post/" . $post->id;
                $notification->message = "$salutation " . $seller->username . ", "
                . "Nous vous informons que votre article \"{$post->titre}\" a été commandé par $buyerPseudo. "
                . "Veuillez préparer l'article pour l'expédition. Un livreur de notre partenaire logistique "
                . "vous contactera bientôt et passera pour récupérer l'article.<br>"
                . "Merci de bien vouloir <a href='/informations?section=cord' class='underlined-link'>cliquer ici</a> pour confirmer ou mettre à jour vos informations bancaires (RIB), "
                . "afin que nous puissions vous transférer les fonds lorsque le processus de vente sera finalisé."
                ;
                $notification->save();
                event(new UserEvent($post->id_user));
            }
        }

        // Send email to the buyer
        Mail::to(Auth::user()->email)->send(new commande($this->user, $this->articles_panier));

        // Generate random token for redirection
        $token = md5(uniqid(rand(), true));

        // Delete cart cookies
        Cookie::queue(Cookie::forget('cart'));

        // Redirect to the next step in the checkout process
        return Redirect("/checkout?step=4&action=$token");
    }

}
