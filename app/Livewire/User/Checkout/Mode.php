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
use App\Models\Commande as CommandeModel;
use App\Models\sous_categories;
use App\Models\regions_categories;
use App\Models\Shipment;
use App\Models\notifications;
use App\Events\UserEvent;
use Illuminate\Support\Facades\DB;
use App\Mail\VenteConfirmee;
use App\Services\AramexService;
use DateTime;
use App\Events\AdminEvent;
use Livewire\Component;

class Mode extends Component
{
    public $user,$articles_panier;
    // public $frais  = 0 ;
    protected $lastShipmentId = null;


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
        $articles_panier = [];
        $user_id = auth()->id();

        $cartItems = UserCart::where('user_id', $user_id)->pluck('post_id');

        foreach ($cartItems as $item_id) {
            $post = posts::join('sous_categories', 'posts.id_sous_categorie', '=', 'sous_categories.id')
                ->join('categories', 'sous_categories.id_categorie', '=', 'categories.id')
                ->select("categories.pourcentage_gain", "posts.prix", "posts.id_user", "posts.id_sous_categorie", "posts.id",  "posts.titre", "posts.photos", "posts.old_prix")
                ->where("posts.id", $item_id)
                ->first();

            if ($post) {

                $id_categorie = $post->id_sous_categorie
                ? sous_categories::where('id', $post->id_sous_categorie)->value('id_categorie')
                : null;

                $id_region = Auth::user()->region ?? null;
                $frais  = 0;
                if ($id_categorie && $id_region) {
                    $regionCategory = regions_categories::where('id_region', $id_region)
                        ->where('id_categorie', $id_categorie)
                        ->first();
                        $frais = $regionCategory ? (float) $regionCategory->prix : 0;
                }
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
        $groupedByVendor = collect($this->articles_panier)->groupBy('vendeur');
        $uniqueVendorsCount = $groupedByVendor->count();
        $totalDeliveryFees = $uniqueVendorsCount > 0 ? $frais * $uniqueVendorsCount : 0;
        $totalWithDelivery = $total + $totalDeliveryFees;

        return view('livewire.user.checkout.mode')
            ->with("total", $total)
            ->with("nbre_article", $nbre_article)
            ->with("totalDeliveryFees", $totalDeliveryFees)
            ->with("totalWithDelivery", $totalWithDelivery);
    }
    public function confirm()
    {
        $this->processCartItems();
        $this->sendBuyerNotification();
        $this->notifySellers();
        $this->sendConfirmationEmail();
        $this->notifyAdminAboutPurchase();

        $token = md5(uniqid(rand(), true));
        Cookie::queue(Cookie::forget('cart'));
        return redirect("/checkout?step=4&action=$token");
    }

    private function notifyAdminAboutPurchase()
    {
        $buyer = Auth::user();
        $itemCount = count($this->articles_panier);

        $notification = new notifications();
        $notification->type = "new_post";
        $notification->titre = $buyer->username . " a acheté $itemCount article(s)";
        $notification->url = "/admin/orders";
        $notification->message = "Nouvelle commande passée par " . $buyer->username;
        $notification->destination = "admin";
        $notification->save();

        event(new AdminEvent('Une nouvelle commande a été passée.'));
    }

    private function processCartItems()
    {
        foreach ($this->articles_panier as $article) {
            $post = posts::find($article['id']);
            if (!$post) continue;

            // Update post status
            $post->update([
                'statut' => 'préparation',
                'sell_at' => now(),
                'id_user_buy' => Auth::id()
            ]);

            // Calculate delivery fees
            $id_categorie = $post->id_sous_categorie ? sous_categories::where('id', $post->id_sous_categorie)->value('id_categorie') : null;
            $id_region = Auth::user()->region ?? null;
            $frais = 0;
            if ($id_categorie && $id_region) {
                $regionCategory = regions_categories::where('id_region', $id_region)
                    ->where('id_categorie', $id_categorie)
                    ->first();
                $frais = $regionCategory ? (float)$regionCategory->prix : 0;
            }

            // Create order
            $commande = new CommandeModel();
            $commande->id_vendor = $post->id_user;
            $commande->id_buyer = Auth::id();
            $commande->id_post = $post->id;
            $commande->frais_livraison = $frais;
            $commande->etat = 'En attente';
            $commande->statut = 'crée';
            $commande->save();
        }
    }

    private function sendBuyerNotification()
    {
        $buyer = Auth::user();
        $salutations = $buyer->gender === 'female'
            ? __('notifications.salutation_female')
            : __('notifications.salutation_male');

        $lastCommandeId = CommandeModel::where('id_buyer', $buyer->id)
                    ->latest('created_at')
                    ->value('id');

        $notification = new notifications();
        $notification->titre = __('notifications.order_confirmed_title');
        $notification->id_user_destination = $buyer->id;
        $notification->type = "alerte";
        $notification->url = "/informations?section=commandes";
        $notification->message = __('notifications.order_confirmed_message', [
            'salutations' => $salutations,
            'shipment_id' => 'CMD-' . $lastCommandeId,
        ]);
        $notification->save();

        event(new UserEvent($buyer->id));
    }

    private function notifySellers()
    {
        $vendeurUsernames = array_unique(array_column($this->articles_panier, 'vendeur'));
        $vendeurs = User::whereIn('username', $vendeurUsernames)->get()->keyBy('username');
        $buyerPseudo = Auth::user()->username;

        foreach ($vendeurUsernames as $sellerUsername) {
            $seller = $vendeurs[$sellerUsername] ?? null;
            if (!$seller) continue;

            $articlesPourCeVendeur = array_filter($this->articles_panier, fn($a) => $a['vendeur'] === $sellerUsername);
            if (empty($articlesPourCeVendeur)) continue;

            $this->sendSellerEmail($seller, $buyerPseudo, $articlesPourCeVendeur);
            $this->createSellerNotification($seller, $buyerPseudo, $articlesPourCeVendeur);
        }
    }

    private function sendSellerEmail($seller, $buyerPseudo, $articlesPourCeVendeur)
    {
        $salutation = $seller->gender === 'female'
            ? __('notifications.salutation_female')
            : __('notifications.salutation_male');

        $postIds = collect($articlesPourCeVendeur)->pluck('id')->filter();
        $posts = posts::whereIn('id', $postIds)->get()->keyBy('id');

        $articlesWithGain = collect($articlesPourCeVendeur)->map(function ($article) use ($posts) {
            $post = $posts[$article['id']] ?? null;
            $article['gain'] = $post ? $post->calculateGain() : 0;
            return $article;
        });

        try {
            Mail::to($seller->email)->send(new VenteConfirmee(
                $seller,
                $buyerPseudo,
                $articlesWithGain,
                $salutation
            ));
        } catch (\Exception $e) {
            logger("❌ Failed to send email to: {$seller->email}. Error: " . $e->getMessage());
        }
    }

    private function createSellerNotification($seller, $buyerPseudo, $articlesPourCeVendeur)
    {
        $salutation = $seller->gender === 'female'
            ? __('notifications.salutation_female')
            : __('notifications.salutation_male');

        $postIds = collect($articlesPourCeVendeur)->pluck('id')->filter();
        $posts = posts::whereIn('id', $postIds)->get()->keyBy('id');

        $articlesLinks = $posts->map(function ($post) {
            $url = route('details_post2', ['id' => $post->id, 'titre' => $post->titre]);
            return "<a href='{$url}' class='underlined-link'>" . e($post->titre) . "</a>";
        });

        $postTitles = $articlesLinks->count() === 1
            ? $articlesLinks->first()
            : $articlesLinks->implode(', ');

        $notification = new notifications();
        $notification->titre = __('notifications.new_order_title');
        $notification->id_user_destination = $seller->id;
        $notification->type = "alerte";
        $notification->url = "/informations?section=commandes";
        $notification->message = __('notifications.new_order_message', [
            'salutation' => $salutation,
            'seller' => $seller->username,
            'buyer' => $buyerPseudo,
            'post_title' => $postTitles,
            'bank_info_url' => url('/informations?section=cord'),
        ]);
        $notification->save();
        event(new UserEvent($seller->id));
    }

    private function sendConfirmationEmail()
    {
        $totalShippingFees = 0;
        Mail::to(Auth::user()->email)->send(new commande($this->user, $this->articles_panier, $totalShippingFees));
    }

}
