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
use App\Models\sous_categories;
use App\Models\regions_categories;
use App\Models\Shipment;
use App\Models\notifications;
use App\Events\UserEvent;
use Illuminate\Support\Facades\DB;
use App\Mail\VenteConfirmee;
use App\Services\AramexService;
use DateTime;
use Livewire\Component;

class Mode extends Component
{
    public $user,$articles_panier;
    // public $frais  = 0 ;


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
        $aramexService = new AramexService();
        $totalArticles = count($this->articles_panier);
        $totalWeight = 0;
        $totalShippingFees = 0;
        $processedSellers = [];
        foreach ($this->articles_panier as $article) {
            $post = posts::find($article['id']);
            // $gain = $post->calculateGain();
            if ($post) {
                $proprietes = $post->proprietes;
                if (isset($proprietes['Poids']) && $proprietes['Poids'] !== null) {
                    $poids = $proprietes['Poids'];
                } else {
                    $poids = 1.0;
                }

                $totalWeight += $poids;
                $post->update(
                    [
                        'statut' => 'vendu',
                        'sell_at' => now(),
                        'id_user_buy' => Auth::id()
                    ]
                );

                $id_categorie = $post->id_sous_categorie ? sous_categories::where('id', $post->id_sous_categorie)->value('id_categorie') : null;
                $id_region = Auth::user()->region ?? null;
                $frais = 0;

                if (!in_array($post->id_user, $processedSellers) && $id_categorie && $id_region) {
                    $regionCategory = regions_categories::where('id_region', $id_region)
                        ->where('id_categorie', $id_categorie)
                        ->first();
                    $frais = $regionCategory ? (float) $regionCategory->prix : 0;
                    $totalShippingFees += $frais;

                    $processedSellers[] = $post->id_user;
                }

                $shippingDateTime = new DateTime();
                $dueDate = (new DateTime())->modify('+4 days');
                $shippingDateTimeAramex = "/Date(" . $shippingDateTime->getTimestamp() * 1000 . "-0000)/";
                $dueDateAramex = "/Date(" . $dueDate->getTimestamp() * 1000 . "-0000)/";

                $shipmentDetails = [
                    'ClientInfo' => [
                        'UserName' => env('ARAMEX_API_USERNAME'),
                        'Password' => env('ARAMEX_API_PASSWORD'),
                        'Version' => env('ARAMEX_API_VERSION'),
                        'AccountNumber' => env('ARAMEX_ACCOUNT_NUMBER'),
                        'AccountPin' => env('ARAMEX_ACCOUNT_PIN'),
                        'AccountEntity' => env('ARAMEX_ACCOUNT_ENTITY'),
                        'AccountCountryCode' => env('ARAMEX_ACCOUNT_COUNTRY_CODE'),
                        'Source' => env('ARAMEX_SOURCE'),
                    ],
                    'Shipments' => [
                        [
                            'Reference1' => 'Order' . $post->id,
                            'Reference2' => '',
                            'Reference3' => '',
                            'Shipper' => [
                                'Reference1' => 'Shop Address',
                                'Reference2' => '',
                                'AccountNumber' => env('ARAMEX_ACCOUNT_NUMBER'),
                                'PartyAddress' => [
                                    'Line1' => $this->user->address,
                                    'Line2' => '',
                                    'Line3' => '',
                                    'City' =>  trim($this->user->address),
                                    'StateOrProvinceCode' => '',
                                    'PostCode' => '23000',
                                    'CountryCode' => 'MA',
                                    'Longitude' => 0,
                                    'Latitude' => 0,
                                    'BuildingNumber' => null,
                                    'BuildingName' => null,
                                    'Floor' => null,
                                    'Apartment' => null,
                                    'POBox' => null,
                                    'Description' => null
                                ],
                                'Contact' => [
                                    'Department' => '',
                                    'PersonName' => 'Shopin',
                                    'Title' => '',
                                    'CompanyName' => 'Shopin',
                                    'PhoneNumber1' => '1234567890',
                                    'PhoneNumber1Ext' => '',
                                    'PhoneNumber2' => '1234567890',
                                    'PhoneNumber2Ext' => '',
                                    'FaxNumber' => '',
                                    'CellPhone' => '1234567890',
                                    'EmailAddress' => 'hazarne14@gmail.com',
                                    'Type' => ''
                                ]
                            ],
                            'Consignee' => [
                                'Reference1' => '',
                                'Reference2' => '',
                                'AccountNumber' => '',
                                'PartyAddress' => [
                                    'Line1' => $this->user->address,
                                    'Line2' => '',
                                    'Line3' => '',
                                    'City' => $this->user->address,
                                    'StateOrProvinceCode' => '',
                                    'PostCode' => '23000',
                                    'CountryCode' => 'MA',
                                    'Longitude' => 0,
                                    'Latitude' => 0,
                                    'BuildingNumber' => '',
                                    'BuildingName' => '',
                                    'Floor' => '',
                                    'Apartment' => '',
                                    'POBox' => null,
                                    'Description' => ''
                                ],
                                'Contact' => [
                                    'Department' => '',
                                    'PersonName' => $this->user->username,
                                    'Title' => '',
                                    'CompanyName' => $this->user->username,
                                    'PhoneNumber1' => $this->user->phone_number,
                                    'PhoneNumber1Ext' => '',
                                    'PhoneNumber2' => $this->user->phone_number,
                                    'PhoneNumber2Ext' => '',
                                    'FaxNumber' => '',
                                    'CellPhone' => $this->user->phone_number,
                                    'EmailAddress' => $this->user->email,
                                    'Type' => ''
                                ]
                            ],
                            'Details' => [
                                'Dimensions' => null,
                                'ActualWeight' => ['Value' => $totalWeight, 'Unit' => 'KG'],
                                'ChargeableWeight' => null,
                                'DescriptionOfGoods' => $post->titre,
                                'GoodsOriginCountry' => "MA",
                                'NumberOfPieces' => $totalArticles,
                                'ProductGroup' => 'DOM',
                                'ProductType' => 'CDS',
                                'PaymentType' => 'P',
                                'PaymentOptions' => '',
                                'CustomsValueAmount' => null,
                                'CashOnDeliveryAmount' => null,
                                'InsuranceAmount' => null,
                                'CashAdditionalAmount' => null,
                                'CashAdditionalAmountDescription' => '',
                                'CollectAmount' => null,
                                'Services' => '',
                                'Items' => []
                            ],
                            'ShippingDateTime' => $shippingDateTimeAramex,
                            'DueDate'  => $dueDateAramex,
                            'Attachments' => [],
                            'ForeignHAWB' => '',
                            'TransportType' => 0,
                            'PickupGUID' => '',
                            'Number' => null,
                            'ScheduledDelivery' => null
                        ]
                    ],
                    'Transaction' => [
                        'Reference1' => '',
                        'Reference2' => '',
                        'Reference3' => '',
                        'Reference4' => '',
                        'Reference5' => ''
                    ]
                ];

                try {
                    $response = $aramexService->sendRequest('Shipping/Service_1_0.svc/json/CreateShipments', $shipmentDetails);
                    if (!isset($response['HasErrors']) || !$response['HasErrors']) {
                        session()->flash('success', 'Expédition créée avec succès!');
                        session()->flash('success_details', json_encode($response, JSON_PRETTY_PRINT));

                        $shipment = new Shipment([
                            'shipment_id' => $response['Shipments'][0]['ID'],
                            'client_info' => $shipmentDetails['ClientInfo'],
                            'shipment_details' => $response['Shipments'][0],
                            'origin' => $response['Shipments'][0]['ShipmentDetails']['Origin'],
                            'destination' => $response['Shipments'][0]['ShipmentDetails']['DestinationCity'],
                            'status' => 'created',
                            'tracking_number' => $response['Shipments'][0]['ID'],
                            'request_data' => $shipmentDetails,
                            'response_data' => $response
                        ]);
                        $shipment->save();

                    } else {
                        // Log and display each error
                        foreach ($response['Notifications'] as $notification) {
                            session()->flash('error', 'Erreur: ' . $notification['Message']);
                        }
                    }
                } catch (\Exception $e) {
                    session()->flash('error', 'Erreur interne: ' . $e->getMessage());
                }

                $buyerPseudo = Auth::user()->username;

                $groupedBySeller = collect($this->articles_panier)->groupBy('vendeur');
                $vendeurUsernames = $groupedBySeller->keys();
                $vendeurs = User::whereIn('username', $vendeurUsernames)->get()->keyBy('username');

                foreach ($groupedBySeller as $sellerUsername => $articlesPourCeVendeur) {
                    $seller = $vendeurs[$sellerUsername] ?? null;

                    if (!$seller || $articlesPourCeVendeur->isEmpty()) {
                        continue;
                    }

                    // Determine salutation based on gender
                    $salutation = $seller->gender === 'female'
                        ? __('notifications.salutation_female')
                        : __('notifications.salutation_male');

                    // Get IDs of the posts
                    $postIds = $articlesPourCeVendeur->pluck('id')->filter();
                    $posts = posts::whereIn('id', $postIds)->get()->keyBy('id');

                    // Attach gain to each article
                    $articlesWithGain = $articlesPourCeVendeur->map(function ($article) use ($posts) {
                        $post = $posts[$article['id']] ?? null;
                        $article['gain'] = $post ? $post->calculateGain() : 0;
                        return $article;
                    });

                    // Send the email
                    Mail::to($seller->email)->send(new VenteConfirmee(
                        $seller,
                        $buyerPseudo,
                        $articlesWithGain,
                        $salutation
                    ));
                }

                $notification = new notifications();
                $notification->titre = __('notifications.new_order_title');
                $notification->id_user_destination = $post->id_user;
                $notification->type = "alerte";
                $notification->url = "/post/" . $post->id;
                $notification->message = __('notifications.new_order_message', [
                    'salutation' => $salutation,
                    'seller' => $seller->username,
                    'post_url' => route('details_post2', ['id' => $post->id, 'titre' => $post->titre]),
                    'post_title' => $post->titre,
                    'buyer' => $buyerPseudo,
                    'bank_info_url' => url('/informations?section=cord'),
                ]);
                $notification->save();
                event(new UserEvent($post->id_user));
            }

        }

        Mail::to(Auth::user()->email)->send(new commande($this->user, $this->articles_panier, $totalShippingFees));
        $token = md5(uniqid(rand(), true));
        Cookie::queue(Cookie::forget('cart'));
        return Redirect("/checkout?step=4&action=$token");
    }

}
