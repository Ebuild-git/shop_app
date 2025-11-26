<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{regions, posts, UserCart};

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
     *     tags={"Panier"},
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

        $user = auth()->user();

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



}
