<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{regions, posts, UserCart, regions_categories, sous_categories};

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


}
