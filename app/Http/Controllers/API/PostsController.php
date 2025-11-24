<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\favoris;
use App\Models\posts;

class PostsController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/favorites/toggle",
     *     tags={"Favorites"},
     *     summary="Add or remove a post from favorites",
     *     description="Toggle a post as favorite by specifying an action flag (add/remove). Requires Bearer Token authentication.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_post", "action"},
     *             @OA\Property(property="id_post", type="integer", example=30, description="ID of the post"),
     *             @OA\Property(property="action", type="string", enum={"add", "remove"}, example="add", description="Action to perform")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Favorite action processed successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="action", type="string", example="add"),
     *             @OA\Property(property="message", type="string", example="Item added to favorites!")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Item not found in favorites!")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Missing or invalid token",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function toggleFavorite(Request $request)
    {
        $request->validate([
            'id_post' => 'required|integer|exists:posts,id',
            'action' => 'required|in:add,remove'
        ]);

        $user = $request->user();
        $userId =$user->id;
        $postId = $request->id_post;
        $action = $request->action;

        $favorite = favoris::where('id_post', $postId)
            ->where('id_user', $userId)
            ->first();

        if ($action === 'add') {

            if ($favorite) {
                return response()->json([
                    "status" => false,
                    "action" => "add",
                    "message" => "Item already in favorites!"
                ]);
            }

            favoris::create([
                'id_post' => $postId,
                'id_user' => $userId
            ]);

            return response()->json([
                "status" => true,
                "action" => "add",
                "message" => "Item added to favorites!"
            ]);
        }

        // action = remove
        if (!$favorite) {
            return response()->json([
                "status" => false,
                "action" => "remove",
                "message" => "Item not found in favorites!"
            ]);
        }

        $favorite->delete();

        return response()->json([
            "status" => true,
            "action" => "remove",
            "message" => "Item removed from favorites!"
        ]);
    }


}
