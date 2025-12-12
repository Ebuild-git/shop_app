<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{regions, posts, UserCart, regions_categories, sous_categories};
use App\Models\Order;
use App\Models\OrdersItem;
use App\Models\User;
use App\Models\notifications;
use Illuminate\Support\Facades\Mail;
use App\Mail\VenteConfirmee;
use App\Mail\commande;
use App\Events\UserEvent;
use App\Events\AdminEvent;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/regions",
     *     operationId="getRegions",
     *     tags={"Location"},
     *     summary="Get all regions",
     *     description="Returns a list of all regions with their IDs and names.",
     *     @OA\Response(
     *         response=200,
     *         description="List of regions",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nom", type="string", example="Béni Mellal-Khénifra"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-03T09:58:34.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-03T09:58:34.000000Z")
     *             )
     *         )
     *     )
     * )
     */
    public function regions()
    {
        $regions = regions::all();
        return response()->json($regions);
    }

    /**
     * @OA\Post(
     *     path="/api/panier/toggle",
     *     tags={"Cart"},
     *     summary="Ajouter ou retirer un article du panier",
     *     description="Ajoute un article au panier si non existant. Le retire sinon. Retourne un flag 'added' ou 'removed'.",
     *
     *     security={{ "bearerAuth":{} }},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id"},
     *             @OA\Property(
     *                 property="id",
     *                 type="integer",
     *                 description="ID du post à ajouter/retirer du panier",
     *                 example=42
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Résultat de l'opération (ajout ou suppression)",
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Article introuvable ou non disponible à la vente"
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="L'utilisateur tente d'ajouter son propre article"
     *     )
     * )
     */
    public function toggle_panier(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:posts,id',
            'action' => 'required|in:add,remove'
        ]);

        $post = posts::where("id", $request->id)
                    ->where("statut", "vente")
                    ->first();

        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => "Cet article n'est plus disponible à la vente",
                'exist' => false,
                'action' => null
            ], 404);
        }

        if ($post->id_user == auth()->id()) {
            return response()->json([
                'status' => false,
                'message' => "Vous ne pouvez pas ajouter votre propre article dans votre panier",
                'exist' => false,
                'action' => null
            ], 403);
        }

        $user = $request->user();

        if ($request->action === "add") {

            $exists = UserCart::where('user_id', $user->id)
                            ->where('post_id', $post->id)
                            ->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => "This item is already in your cart",
                    'exist' => true,
                    'action' => "add"
                ]);
            }

            UserCart::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);

            return response()->json([
                'status' => true,
                'message' => "Item added successfully",
                'exist' => true,
                'action' => "add"
            ]);
        }

        if ($request->action === "remove") {

            UserCart::where('user_id', $user->id)
                    ->where('post_id', $post->id)
                    ->delete();

            return response()->json([
                'status' => true,
                'message' => "Item removed from your cart",
                'exist' => false,
                'action' => "remove"
            ]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/cart",
     *     tags={"Cart"},
     *     summary="Fetch logged-in user's cart",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Cart content response"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $user_id = $user->id;

        $cartItems = UserCart::where('user_id', $user_id)->pluck('post_id');
        $articles_panier = [];
        $total = 0;

        foreach ($cartItems as $item) {

            $post = posts::join('sous_categories', 'posts.id_sous_categorie', '=', 'sous_categories.id')
                ->join('categories', 'sous_categories.id_categorie', '=', 'categories.id')
                ->select("categories.pourcentage_gain", "posts.prix", "posts.id_user", "posts.id_sous_categorie",
                         "posts.id", "posts.titre", "posts.photos", "posts.old_prix")
                ->where("posts.id", $item)
                ->first();

            if ($post) {

                $id_categorie = $post->id_sous_categorie
                    ? sous_categories::where('id', $post->id_sous_categorie)->value('id_categorie')
                    : null;

                $id_region = $request->user()->region ?? null;

                $fraisLivraison = '0,00';

                if ($id_categorie && $id_region) {
                    $regionCategory = regions_categories::where('id_region', $id_region)
                        ->where('id_categorie', $id_categorie)
                        ->first();

                    $fraisLivraison = $regionCategory
                        ? number_format($regionCategory->prix, 2, ',', '')
                        : '0,00';
                }

                $photoFullUrl = null;

                if (!empty($post->photos) && isset($post->photos[0])) {
                    $photoFullUrl = url('storage/' . $post->photos[0]);
                }

                $articles_panier[] = [
                    "id"        => $post->id,
                    "titre"     => $post->titre,
                    "prix"      => $post->getPrix(),
                    "photo"     => $photoFullUrl,
                    "vendeur"   => $post->user_info->username,
                    "is_solder" => $post->old_prix ? true : false,
                    "old_prix"  => $post->getOldPrix(),
                    "frais"     => $fraisLivraison,
                ];

                $total += round($post->getPrix(), 3);
            }
        }

        return response()->json([
            "success" => true,
            "articles" => $articles_panier,
            "total" => $total
        ]);
    }


    /**
     * @OA\Delete(
     *     path="/api/cart/{post_id}",
     *     tags={"Cart"},
     *     summary="Delete an item from the cart",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="post_id",
     *         in="path",
     *         required=true,
     *         description="ID of post to remove from cart",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Item deleted"
     *     )
     * )
     */
    public function delete(Request $request, $id)
    {
        $user = $request->user();
        $user_id = $user->id;

        UserCart::where('user_id', $user_id)
            ->where('post_id', $id)
            ->delete();

        return response()->json([
            "success" => true,
            "message" => "Item removed from cart"
        ]);
    }


    /**
     * @OA\Delete(
     *     path="/api/cart",
     *     tags={"Cart"},
     *     summary="Empty entire cart",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Cart cleared"
     *     )
     * )
     */
    public function clear(Request $request)
    {
        $user = $request->user();
        $user_id = $user->id;

        UserCart::where('user_id', $user_id)->delete();

        return response()->json([
            "success" => true,
            "message" => "Cart cleared"
        ]);
    }


    public function cartSummary(Request $request)
    {
        $user = $request->user();
        $cartItems = UserCart::where('user_id', $user->id)->pluck('post_id');

        $articles_panier = [];
        $total = 0;
        $frais = 0;

        foreach ($cartItems as $item_id) {
            $post = posts::with('user_info')
                ->join('sous_categories', 'posts.id_sous_categorie', '=', 'sous_categories.id')
                ->join('categories', 'sous_categories.id_categorie', '=', 'categories.id')
                ->select("categories.pourcentage_gain", "posts.prix", "posts.id_user", "posts.id_sous_categorie", "posts.id",  "posts.titre", "posts.photos", "posts.old_prix")
                ->where("posts.id", $item_id)
                ->first();

            if (!$post) continue;

            $id_categorie = $post->id_sous_categorie ? sous_categories::where('id', $post->id_sous_categorie)->value('id_categorie') : null;
            $id_region = $user->region ?? null;

            if ($id_categorie && $id_region) {
                $regionCategory = regions_categories::where('id_region', $id_region)
                    ->where('id_categorie', $id_categorie)
                    ->first();
                $frais = $regionCategory ? (float)$regionCategory->prix : 0;
            }

            $articles_panier[] = [
                "id" => $post->id,
                "titre" => $post->titre,
                "prix" => $post->getPrix(),
                "photo" => config('app.url').\Storage::url($post->photos[0]),
                "vendeur" => $post->user_info->username,
                "is_solder" => $post->old_prix ? true : false,
                "old_prix" => $post->old_prix
            ];

            $total += round($post->getPrix(), 3);
        }

        $groupedByVendor = collect($articles_panier)->groupBy('vendeur');
        $uniqueVendorsCount = $groupedByVendor->count();
        $totalDeliveryFees = $uniqueVendorsCount > 0 ? $frais * $uniqueVendorsCount : 0;
        $totalWithDelivery = $total + $totalDeliveryFees;

        return response()->json([
            'success' => true,
            'items' => $articles_panier,
            'total' => $total,
            'totalDeliveryFees' => $totalDeliveryFees,
            'totalWithDelivery' => $totalWithDelivery
        ]);
    }


    /**
     * @OA\Post(
     *      path="/api/order/confirm",
     *      operationId="confirmOrder",
     *      tags={"Cart"},
     *      summary="Confirm an order",
     *      description="Confirms a pending order and updates its status.",
     *      security={{"sanctum": {}}},
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"order_id"},
     *              @OA\Property(
     *                  property="order_id",
     *                  type="integer",
     *                  example=123,
     *                  description="ID of the order to confirm"
     *              )
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Order confirmed successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Order confirmed successfully")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Invalid request data"
     *      ),
     *
     *      @OA\Response(
     *          response=404,
     *          description="Order not found"
     *      )
     * )
     */
    public function confirm(Request $request)
    {
        $user = $request->user();

        $cartItemIds = UserCart::where('user_id', $user->id)->pluck('post_id');
        if ($cartItemIds->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Cart is empty'], 422);
        }

        $articles_panier = [];
        foreach ($cartItemIds as $id) {
            $post = posts::with('user_info')->find($id);
            if (!$post) continue;

            $articles_panier[] = [
                'id' => $post->id,
                'titre' => $post->titre,
                'prix' => $post->getPrix(),
                "photo" => config('app.url').Storage::url($post->photos[0]),
                'vendeur' => $post->user_info->username,
                'old_prix' => $post->old_prix
            ];
        }

        $order = Order::create([
            'buyer_id' => $user->id,
            'total' => 0,
            'total_delivery_fees' => 0,
            'status' => 'pending',
            'state' => 'created',
        ]);

        $total = 0;
        $totalDeliveryFees = 0;
        $vendorsCounted = [];

        foreach ($articles_panier as $article) {
            $post = posts::find($article['id']);
            if (!$post) continue;

            $post->update([
                'statut' => 'préparation',
                'sell_at' => now(),
                'id_user_buy' => $user->id
            ]);

            $id_categorie = $post->id_sous_categorie
                ? sous_categories::where('id', $post->id_sous_categorie)->value('id_categorie')
                : null;
            $id_region = $user->region ?? null;
            $frais = 0;

            if ($id_categorie && $id_region) {
                $regionCategory = regions_categories::where('id_region', $id_region)
                    ->where('id_categorie', $id_categorie)
                    ->first();
                $frais = $regionCategory ? (float)$regionCategory->prix : 0;
            }

            if (!isset($vendorsCounted[$post->id_user])) {
                $totalDeliveryFees += $frais;
                $vendorsCounted[$post->id_user] = true;
            }

            OrdersItem::create([
                'order_id' => $order->id,
                'post_id' => $post->id,
                'vendor_id' => $post->id_user,
                'price' => $post->getPrix(),
                'delivery_fee' => $frais,
                'status' => 'pending',
            ]);

            $total += $post->getPrix();
        }

        $order->update([
            'total' => $total,
            'total_delivery_fees' => $totalDeliveryFees,
        ]);

        $this->sendBuyerNotification($user, $order);

        $this->notifySellers($user, $articles_panier);

        $this->notifyAdminAboutPurchase($user, count($articles_panier));

        $this->sendConfirmationEmail($user, $articles_panier);

        UserCart::where('user_id', $user->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order confirmed',
            'order_id' => $order->id
        ]);
    }

    private function sendBuyerNotification($buyer, $order)
    {
        $salutations = $buyer->gender === 'female'
            ? __('notifications.salutation_female')
            : __('notifications.salutation_male');

        $notification = new notifications();
        $notification->titre = __('notifications.order_confirmed_title');
        $notification->id_user_destination = $buyer->id;
        $notification->type = "alerte";
        $notification->url = "/informations?section=commandes";
        $notification->message = __('notifications.order_confirmed_message', [
            'salutations' => $salutations,
            'shipment_id' => 'CMD-' . $order->id,
        ]);
        $notification->save();

        event(new UserEvent($buyer->id));
    }

    private function notifySellers($buyer, $articles)
    {
        $vendeurUsernames = array_unique(array_column($articles, 'vendeur'));
        $vendeurs = User::whereIn('username', $vendeurUsernames)->get()->keyBy('username');
        $buyerPseudo = $buyer->username;

        foreach ($vendeurUsernames as $username) {
            $seller = $vendeurs[$username] ?? null;
            if (!$seller) continue;

            $articlesPourCeVendeur = array_filter($articles, fn($a) => $a['vendeur'] === $username);
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
            logger("Failed to send email to {$seller->email}: " . $e->getMessage());
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

    private function notifyAdminAboutPurchase($buyer, $itemCount)
    {
        $notification = new notifications();
        $notification->type = "new_post";
        $notification->titre = $buyer->username . " a acheté $itemCount article(s)";
        $notification->url = "/admin/orders";
        $notification->message = "Nouvelle commande passée par " . $buyer->username;
        $notification->destination = "admin";
        $notification->save();

        event(new AdminEvent('Une nouvelle commande a été passée.'));
    }

    private function sendConfirmationEmail($user, $articles_panier)
    {
        $groupedByVendor = collect($articles_panier)->groupBy('vendeur');
        $uniqueVendorsCount = $groupedByVendor->count();

        $frais = 0;
        if (!empty($articles_panier)) {
            $firstArticle = $articles_panier[0];
            $post = posts::find($firstArticle['id']);
            if ($post) {
                $id_categorie = $post->id_sous_categorie
                    ? sous_categories::where('id', $post->id_sous_categorie)->value('id_categorie')
                    : null;
                $id_region = $user->region ?? null;

                if ($id_categorie && $id_region) {
                    $regionCategory = regions_categories::where('id_region', $id_region)
                        ->where('id_categorie', $id_categorie)
                        ->first();
                    $frais = $regionCategory ? (float) $regionCategory->prix : 0;
                }
            }
        }

        $totalShippingFees = $uniqueVendorsCount > 0 ? $frais * $uniqueVendorsCount : 0;
        Mail::to($user->email)->send(new commande($user, $articles_panier, $totalShippingFees));
    }

    /**
     * @OA\Get(
     *      path="/orders/{id}/track",
     *      operationId="trackOrder",
     *      tags={"Cart"},
     *      summary="Track an order using its ID",
     *      description="Returns order information, buyer details, and associated items for tracking purposes.",
     *      security={{"sanctum": {}}},

    *      @OA\Parameter(
    *          name="id",
    *          in="path",
    *          required=true,
    *          description="Order ID",
    *          @OA\Schema(type="integer", example=45)
    *      ),

    *      @OA\Response(
    *          response=200,
    *          description="Order found",
    *          @OA\JsonContent(
    *              @OA\Property(property="order_id", type="integer", example=45),
    *              @OA\Property(property="status", type="string", example="confirmed"),
    *              @OA\Property(property="state", type="string", example="in_progress"),
    *              @OA\Property(property="total", type="number", example=180),
    *              @OA\Property(property="total_delivery_fees", type="number", example=12),

    *              @OA\Property(
    *                  property="buyer",
    *                  type="object",
    *                  @OA\Property(property="id", type="integer", example=14),
    *                  @OA\Property(property="username", type="string", example="hazar"),
    *                  @OA\Property(property="email", type="string", example="hazar@test.com")
    *              ),

    *              @OA\Property(
    *                  property="items",
    *                  type="array",
    *                  @OA\Items(
    *                      @OA\Property(property="post_id", type="integer", example=33),
    *                      @OA\Property(property="vendor_id", type="integer", example=4),
    *                      @OA\Property(property="price", type="number", example=40),
    *                      @OA\Property(property="delivery_fee", type="number", example=5),
    *                      @OA\Property(property="status", type="string", example="shipped"),
    *                      @OA\Property(property="shipment_id", type="string", example="SHIP-88991")
    *                  )
    *              ),

    *              @OA\Property(property="updated_at", type="string", example="2025-12-12 14:30:00")
    *          )
    *      ),

    *      @OA\Response(
    *          response=404,
    *          description="Order not found"
    *      )
    * )
    */
    public function track($id)
    {
        $order = Order::with([
            'buyer:id,username,email',
            'items' => function ($q) {
                        $q->select('id', 'order_id', 'post_id', 'vendor_id', 'price', 'delivery_fee', 'status', 'shipment_id')
                        ->with([
                            'post:id,titre,photos,statut'
                        ]);
                    }
                ])->find($id);

                if (!$order) {
                    return response()->json(['error' => 'Order not found'], 404);
                }

                return response()->json([
                    'order_id'            => $order->id,
                    'buyer'               => $order->buyer,
                    'status'              => $order->status,
                    'state'               => $order->state,
                    'total'               => $order->total,
                    'total_delivery_fees' => $order->total_delivery_fees,

                    'items' => $order->items->map(function ($item) {

                $photos = [];
                if ($item->post) {
                    $photos = is_string($item->post->photos)
                        ? json_decode($item->post->photos, true)
                        : (is_array($item->post->photos) ? $item->post->photos : []);
                }

                $firstPhoto = $photos[0] ?? null;

                $photoUrl = $firstPhoto ? asset('storage/' . $firstPhoto) : null;

                return [
                    'post_id'      => $item->post_id,
                    'vendor_id'    => $item->vendor_id,
                    'price'        => $item->price,
                    'delivery_fee' => $item->delivery_fee,
                    'status'       => $item->status,
                    'shipment_id'  => $item->shipment_id,

                    'post' => [
                        'title'  => $item->post->titre ?? null,
                        'image'  => $photoUrl,
                        'statut' => $item->post->statut ?? null,
                    ],
                ];
            }),


            'updated_at' => $order->updated_at,
        ]);
    }


}
