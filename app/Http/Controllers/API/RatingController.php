<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\posts;
use App\Models\ratings;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrdersItem;

class RatingController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/users/rating/purchases",
     *     summary="Get all purchases for authenticated user",
     *     description="Retrieve a list of all purchases made by the authenticated user with rating information",
     *     operationId="getUserPurchases",
     *     tags={"Ratings"},
     *     security={{ "bearerAuth": {} }},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="total_purchases", type="integer", example=5),
     *             @OA\Property(
     *                 property="purchases",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="purchase",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=123),
     *                         @OA\Property(property="title", type="string", example="iPhone 13 Pro"),
     *                         @OA\Property(property="statut", type="string", example="livré", enum={"livré", "préparation", "vendu", "annulé"}),
     *                         @OA\Property(property="sell_at", type="string", format="date-time", example="2024-01-15 14:30:00"),
     *                         @OA\Property(property="price", type="number", format="float", example=999.99)
     *                     ),
     *                     @OA\Property(
     *                         property="seller",
     *                         type="object",
     *                         nullable=true,
     *                         @OA\Property(property="id", type="integer", example=456),
     *                         @OA\Property(property="username", type="string", example="john_doe")
     *                     ),
     *                     @OA\Property(
     *                         property="rating",
     *                         type="object",
     *                         nullable=true,
     *                         @OA\Property(property="id", type="integer", example=789),
     *                         @OA\Property(property="etoiles", type="integer", example=5),
     *                         @OA\Property(property="comment", type="string", example="Great seller!"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-16 10:00:00")
     *                     ),
     *                     @OA\Property(property="can_rate", type="boolean", example=true),
     *                     @OA\Property(property="rating_deadline", type="string", format="date-time", nullable=true, example="2024-01-29 14:30:00")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="No purchases found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No purchases found"),
     *             @OA\Property(property="purchases", type="array", @OA\Items())
     *         )
     *     )
     * )
     */
    public function show(Request $request)
    {
        $authUser = $request->user();

        $purchases = posts::where('id_user_buy', $authUser->id)
            ->orderBy('sell_at', 'desc')
            ->get();

        if ($purchases->isEmpty()) {
            return response()->json([
                'message' => 'No purchases found',
                'purchases' => []
            ], 404);
        }

        $purchaseIds = $purchases->pluck('id')->toArray();
        $ratings = ratings::where('id_user_buy', $authUser->id)
            ->whereIn('id_post', $purchaseIds)
            ->get()
            ->keyBy('id_post');

        $sellerIds = $purchases->pluck('id_user')->unique()->toArray();
        $sellers = User::whereIn('id', $sellerIds)
            ->get()
            ->keyBy('id');

        $result = [];

        foreach ($purchases as $purchase) {
            $seller = $sellers[$purchase->id_user] ?? null;
            $rate = $ratings[$purchase->id] ?? null;
            $can_rate = false;
            if (!$rate && $purchase->statut === 'livré') {
                $can_rate = $purchase->sell_at >= Carbon::now()->subDays(14);
            }

            $result[] = [
                'purchase' => [
                    'id' => $purchase->id,
                    'title' => $purchase->titre,
                    'statut' => $purchase->statut,
                    'sell_at' => $purchase->sell_at,
                    'price' => $purchase->prix ?? null,
                ],
                'seller' => $seller ? [
                    'id' => $seller->id,
                    'username' => $seller->username,
                ] : null,
                'rating' => $rate ? [
                    'id' => $rate->id,
                    'etoiles' => $rate->etoiles,
                    'comment' => $rate->comment ?? null,
                    'created_at' => $rate->created_at,
                ] : null,
                'can_rate' => $can_rate,
                'rating_deadline' => $can_rate ?
                    Carbon::parse($purchase->sell_at)->addDays(14)->format('Y-m-d H:i:s') :
                    null,
            ];
        }

        return response()->json([
            'total_purchases' => count($result),
            'purchases' => $result
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/users/{purchase_id}/rating",
     *     summary="Submit a rating for a purchase",
     *     description="Rate a specific purchase. Only allowed for delivered purchases within 14 days of delivery.",
     *     operationId="ratePurchase",
     *     tags={"Ratings"},
     *     security={{ "bearerAuth": {} }},
     *
     *     @OA\Parameter(
     *         name="purchase_id",
     *         in="path",
     *         required=true,
     *         description="ID of the purchase to rate",
     *         @OA\Schema(type="integer", example=123)
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Rating data",
     *         @OA\JsonContent(
     *             required={"etoiles"},
     *             @OA\Property(property="etoiles", type="integer", minimum=1, maximum=5, example=5, description="Rating stars (1-5)"),
     *             @OA\Property(property="comment", type="string", maxLength=500, example="Excellent service!", description="Optional comment")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Rating submitted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Rating submitted successfully"),
     *             @OA\Property(
     *                 property="rating",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=789),
     *                 @OA\Property(property="etoiles", type="integer", example=5),
     *                 @OA\Property(property="purchase_id", type="integer", example=123),
     *                 @OA\Property(property="seller_id", type="integer", example=456),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-16 10:00:00")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="etoiles",
     *                     type="array",
     *                     @OA\Items(type="string", example="The etoiles field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Purchase not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Purchase not found or you are not authorized to rate it")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Rating conditions not met",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(
     *                     @OA\Property(property="error", type="string", example="You already rated this purchase")
     *                 ),
     *                 @OA\Schema(
     *                     @OA\Property(property="error", type="string", example="Cannot rate this purchase. Purchase must be delivered.")
     *                 ),
     *                 @OA\Schema(
     *                     @OA\Property(property="error", type="string", example="Rating period expired. You can only rate within 14 days of delivery.")
     *                 )
     *             }
     *         )
     *     )
     * )
     */
    public function store(Request $request, $purchase_id)
    {
        $authUser = $request->user();

        $purchase = posts::where('id', $purchase_id)
            ->where('id_user_buy', $authUser->id)
            ->first();

        if (!$purchase) {
            return response()->json(['error' => 'Purchase not found or you are not authorized to rate it'], 404);
        }

        $seller = User::findOrFail($purchase->id_user);

        $old_rate = ratings::where('id_user_buy', $authUser->id)
            ->where('id_user_sell', $seller->id)
            ->where('id_post', $purchase->id)
            ->first();

        if ($old_rate) {
            return response()->json(['error' => 'You already rated this purchase'], 422);
        }

        if ($purchase->statut !== 'livré') {
            return response()->json(['error' => 'Cannot rate this purchase. Purchase must be delivered.'], 422);
        }

        $date_limit = Carbon::now()->subDays(14);
        if ($purchase->sell_at < $date_limit) {
            return response()->json(['error' => 'Rating period expired. You can only rate within 14 days of delivery.'], 422);
        }

        $request->validate([
            'etoiles' => 'required|integer|min:1|max:5'
        ]);

        $rate = new ratings();
        $rate->id_user_buy = $authUser->id;
        $rate->id_user_sell = $seller->id;
        $rate->id_post = $purchase->id;
        $rate->etoiles = $request->etoiles;
        $rate->save();

        return response()->json([
            'success' => true,
            'message' => 'Rating submitted successfully',
            'rating' => [
                'id' => $rate->id,
                'etoiles' => $rate->etoiles,
                'purchase_id' => $rate->id_post,
                'seller_id' => $rate->id_user_sell,
                'created_at' => $rate->created_at,
            ],
        ], 201);
    }
}
