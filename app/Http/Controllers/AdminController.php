<?php

namespace App\Http\Controllers;

use App\Models\categories;
use App\Models\posts;
use App\Models\proprietes;
use App\Models\User;
use App\Models\UserCart;
use App\Models\regions;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrdersItem;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Commande;
use App\Events\UserEvent;
use App\Models\notifications;
use App\Services\AramexService;
use DateTime;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{


    public function show_admin_dashboard(Request $request)
    {
        $year = (int) $request->input('das_date', date("Y"));

        $stats_inscription = [];
        $stats_publication = [];

        for ($month = 1; $month <= 12; $month++) {
            $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            $stats_inscription[] = User::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->where('role', '!=', 'admin')
                ->where('locked', false)
                ->count();

            $stats_publication[] = posts::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->whereNull('deleted_at')
            ->count();
        }

        $stats_inscription_publication = [
            'inscription' => $stats_inscription,
            'publication' => $stats_publication,
        ];

        $commandes = Commande::OrderBy('created_at', 'desc')
                            ->paginate(10);
        $genres = [
            "homme" => User::where('gender', 'male')->where('role', '!=', 'admin')->where('locked', false)->count(),
            "femme" => User::where('gender', 'female')->where('role', '!=', 'admin')->where('locked', false)->count(),
        ];

        return view('Admin.dashboard', compact("commandes", "year", "stats_inscription_publication", "genres"));
    }

    public function add_sous_categorie($id)
    {
        $categorie = categories::find($id);
        if ($categorie) {
            return view('Admin.categories.add_sous_categorie')->with('categorie', $categorie);
        } else {
            abort(404);
        }
    }


    public function admin_settings()
    {
        return view('Admin.parametre.index');
    }

    public function admin_settings_security()
    {
        return view('Admin.parametre.security');
    }



    public function update_propriete($id)
    {
        $propriete = proprietes::find($id);
        $proprietes = proprietes::all();
        if (!$propriete) {
            abort(404);
        }
        return view("Admin.categories.update_propriete", compact('propriete', "proprietes"));
    }


    public function export_users()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }



    public function index_login()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view("auth.login");
    }


    public function post_login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ], [
            'required' => 'Ce champ est obligatoire.',
            'email' => 'Veuillez entrer une adresse email valide.',
            'exists' => "Cette adresse email n'existe pas.",
        ]);
        $user = User::where('email',$request->email)
            ->where("role", "admin")
            ->first();
        if (!$user) {
            return redirect()->back()->with('error', 'Cet e-mail n\'existe pas autorisé!');
        }
        $remember = $request->has('remember');
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $remember)) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->back()->with('error', 'Echec de connexion');
        }
    }




    public function index_logout(){
        $user = Auth::user();
        $cart = json_decode($_COOKIE['cart'] ?? '[]', true);

        if ($user && $cart) {
            foreach ($cart as $item) {
                $postExists = posts::where('id', $item['id'])->exists();
                if ($postExists) {
                    UserCart::updateOrCreate(
                        ['user_id' => $user->id, 'post_id' => $item['id']]
                    );
                }

            }
        }

        Auth::logout();
        setcookie('cart', '', time() - 3600, '/', null, null, true);
        return redirect('/')->with('clearLocalStorage', true);
    }

    public function index_categories()
    {
        $categories = categories::all();
        $totalCategories = $categories->count();
        return view("Admin.categories.index" , compact("totalCategories"));
    }
    public function index_proprietes(){
        $proprietes = proprietes::all();
        $totalProprietes = $proprietes->count();
        return view("Admin.categories.index_proprietes" , compact("totalProprietes"));
    }



    public function approveCIN($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->cin_approved = true;
            $user->save();

            event(new UserEvent($user->id));
            $notification = new notifications();
            $notification->titre = __('cin_notification_title');
            $notification->id_user_destination = $user->id;
            $notification->type = "alerte";
            $notification->destination = "user";
            $notification->message = __('cin_notification_message');
            $notification->save();


            return response()->json([
                'success' => true,
                'message' => 'CIN approved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error approving CIN: ' . $e->getMessage()
            ], 500);
        }
    }
    public function markAsRead($id)
    {
        $notification = notifications::findOrFail($id);
        $notification->statut = 'read';
        $notification->save();

        return redirect($notification->url);
    }

    public function orders(Request $request)
    {
        $query = Order::with([
            'items.post',
            'items.vendor',
            'buyer'
        ])->orderBy('created_at', 'desc');

        if ($request->filled('region_id')) {
            $regionId = $request->region_id;
            $query->whereHas('items.vendor', fn($q) => $q->where('region', $regionId))
                ->orWhereHas('buyer', fn($q) => $q->where('region', $regionId));
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function($q) use ($search) {

                if (preg_match('/^CMD-(\d+)$/i', $search, $matches)) {
                    $id = $matches[1];
                    $q->where('id', $id);
                } else {
                    $q->whereHas('items.vendor', function($q2) use ($search) {
                        $q2->where('username', 'like', "%{$search}%");
                    })->orWhereHas('buyer', function($q2) use ($search) {
                        $q2->where('username', 'like', "%{$search}%");
                    })->orWhere('shipment_id', 'like', "%{$search}%");
                }
            });
        }

        $orders = $query->paginate(10)->appends($request->all());
        $regions = regions::all();

        return view('Admin.shipement.shipement', compact('orders', 'regions'));
    }

    // public function syncWithAramex($id)
    // {
    //     $order = Order::with(['items.post', 'items.vendor', 'buyer'])->find($id);

    //     if (!$order) {
    //         return response()->json(['success' => false, 'message' => 'Commande introuvable.']);
    //     }

    //     $unsyncedItems = $order->items->filter(fn($item) => !$item->shipment_id);

    //     if ($unsyncedItems->isEmpty()) {
    //         return response()->json(['success' => false, 'message' => 'Tous les articles de cette commande sont déjà synchronisés.']);
    //     }

    //     $aramex = new AramexService();
    //     $results = [];

    //     foreach ($unsyncedItems as $item) {
    //         try {
    //             $payload = $this->buildShipmentPayloadItem($order, $item);

    //             $response = $aramex->sendRequest('/Shipping/Service_1_0.svc/json/CreateShipments', $payload);

    //             if (!isset($response['HasErrors']) || !$response['HasErrors']) {

    //                 $shipmentId = $response['Shipments'][0]['ID']
    //                 ?? $response['Shipments'][0]['ShipmentNumber']
    //                 ?? $response['Shipments'][0]['ShipmentLabel']
    //                 ?? null;

    //                 if (!$shipmentId) {
    //                     Log::warning('⚠️ No shipment ID for item', [
    //                         'order_id' => $order->id,
    //                         'item_id' => $item->id,
    //                         'response' => $response,
    //                     ]);
    //                 }

    //                 $item->shipment_id = $shipmentId;
    //                 $item->status = 'expédiée';
    //                 $item->save();

    //                 $results[] = [
    //                     'item_id' => $item->id,
    //                     'success' => true,
    //                     'shipment_id' => $shipmentId,
    //                 ];

    //             } else {
    //                 $msg = collect($response['Notifications'] ?? [])->pluck('Message')->implode('; ');
    //                 $results[] = [
    //                     'item_id' => $item->id,
    //                     'success' => false,
    //                     'message' => $msg,
    //                 ];
    //             }
    //         } catch (\Exception $e) {
    //             Log::error('🔥 Exception during Aramex sync', [
    //                 'order_id' => $order->id,
    //                 'item_id' => $item->id,
    //                 'error' => $e->getMessage(),
    //             ]);
    //             $results[] = [
    //                 'item_id' => $item->id,
    //                 'success' => false,
    //                 'message' => $e->getMessage(),
    //             ];
    //         }
    //     }

    //     if ($order->items->every(fn($item) => $item->shipment_id)) {
    //         $order->status = 'expédiée';
    //         $order->save();
    //     }

    //     return response()->json(['success' => true, 'results' => $results]);
    // }
    public function syncWithAramex($id)
    {
        $order = Order::with(['items.post', 'items.vendor', 'buyer'])->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Commande introuvable.'
            ]);
        }

        $unsyncedItems = $order->items->filter(fn($item) => !$item->shipment_id);

        if ($unsyncedItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tous les articles de cette commande sont déjà synchronisés.'
            ]);
        }

        $aramex = new AramexService();
        $results = [];

        foreach ($unsyncedItems as $item) {
            try {
                $payload = $this->buildShipmentPayloadItem($order, $item);

                $response = $aramex->sendRequest('/Shipping/Service_1_0.svc/json/CreateShipments', $payload);

                if (!isset($response['HasErrors']) || !$response['HasErrors']) {

                    $shipmentId = $response['Shipments'][0]['ID']
                        ?? $response['Shipments'][0]['ShipmentNumber']
                        ?? $response['Shipments'][0]['ShipmentLabel']
                        ?? null;

                    if (!$shipmentId) {
                        Log::warning('⚠️ No shipment ID returned', [
                            'order_id' => $order->id,
                            'item_id' => $item->id,
                            'response' => $response,
                        ]);
                    }

                    $item->shipment_id = $shipmentId;
                    $item->status = 'expédiée';
                    $item->save();

                    $results[] = [
                        'item_id' => $item->id,
                        'success' => true,
                        'shipment_id' => $shipmentId,
                    ];
                } else {
                    $msg = collect($response['Notifications'] ?? [])->pluck('Message')->implode('; ');
                    $results[] = [
                        'item_id' => $item->id,
                        'success' => false,
                        'message' => $msg ?: 'Erreur inconnue retournée par Aramex.'
                    ];
                }
            } catch (\Exception $e) {
                Log::error('🔥 Exception during Aramex sync', [
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'error' => $e->getMessage(),
                ]);

                $results[] = [
                    'item_id' => $item->id,
                    'success' => false,
                    'message' => $e->getMessage(),
                ];
            }
        }

        $allSucceeded = collect($results)->every(fn($r) => $r['success']);

        if ($allSucceeded) {
            $order->status = 'expédiée';
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Synchronisation Aramex réussie pour tous les articles.',
                'results' => $results,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Certains articles n’ont pas pu être synchronisés avec Aramex.',
            'results' => $results,
        ]);
    }

    private function buildShipmentPayloadItem($order, $item)
    {
        $buyer = $order->buyer;
        $vendor = $item->vendor;
        $post = $item->post;
        $totalArticles = 1;

        $shippingDateTime = new DateTime();
        $dueDate = (new DateTime())->modify('+4 days');
        $shippingDateTimeAramex = "/Date(" . ($shippingDateTime->getTimestamp() * 1000) . "-0000)/";
        $dueDateAramex = "/Date(" . ($dueDate->getTimestamp() * 1000) . "-0000)/";

        return [
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
                'Reference1' => 'CMD-' . $order->id,
                'Reference2' => '',
                'Reference3' => '',
                'Shipper' => [
                    'Reference1' => 'Shop Address',
                    'Reference2' => '',
                    'AccountNumber' => env('ARAMEX_ACCOUNT_NUMBER'),
                    'PartyAddress' => [
                        'Line1' => $vendor->address,
                        'Line2' => '',
                        'Line3' => '',
                        'City' =>  trim($vendor->address),
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
                        'Line1' => $buyer->address,
                        'Line2' => '',
                        'Line3' => '',
                        'City' => $buyer->address,
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
                        'PersonName' => $buyer->firstname . ' ' . $buyer->lastname,
                        'Title' => '',
                        'CompanyName' => $buyer->username,
                        'PhoneNumber1' => $buyer->phone_number,
                        'PhoneNumber1Ext' => '',
                        'PhoneNumber2' => $buyer->phone_number,
                        'PhoneNumber2Ext' => '',
                        'FaxNumber' => '',
                        'CellPhone' => $buyer->phone_number,
                        'EmailAddress' => $buyer->email,
                        'Type' => ''
                    ]
                ],
                'Details' => [
                    'Dimensions' => null,
                    'ActualWeight' => ['Value' => '1', 'Unit' => 'KG'],
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
            'Reference1' => 'CMD-' . $order->id,
            'Reference2' => '',
            'Reference3' => '',
            'Reference4' => '',
            'Reference5' => ''
        ]
        ];
    }


}
