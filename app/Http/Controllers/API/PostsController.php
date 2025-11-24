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

    /**
     * @OA\Get(
     *     path="/api/favorites",
     *     tags={"Favorites"},
     *     summary="List authenticated user's favorite posts",
     *     description="Returns a list of the authenticated user's favorite posts with post details, post owner, and the date the post was favorited.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of favorite posts",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=14),
     *                     @OA\Property(property="titre", type="string", example="iPhone 13 Pro Max"),
     *                     @OA\Property(property="description", type="string", example="Very good condition, 128GB"),
     *                     @OA\Property(
     *                         property="photos",
     *                         type="array",
     *                         @OA\Items(type="string", example="http://127.0.0.1:8000/storage/uploads/posts/img1.jpg")
     *                     ),
     *                     @OA\Property(property="prix", type="number", example=2500),
     *                     @OA\Property(property="etat", type="string", example="Neuf"),
     *                     @OA\Property(property="statut", type="string", example="active"),
     *
     *                     @OA\Property(property="favorited_at", type="string", format="date-time", example="2025-01-10T12:45:23.000000Z"),
     *
     *                     @OA\Property(
     *                         property="post_owner",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=2),
     *                         @OA\Property(property="name", type="string", example="Hazar Nenni"),
     *                         @OA\Property(property="email", type="string", example="user@example.com")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function listFavorites(Request $request)
    {
        $user = $request->user();
        $userId = $user->id;

        $favorites = favoris::where('id_user', $userId)
            ->with([
                'post' => function ($query) {
                    $query->select('id', 'id_user', 'titre', 'description', 'photos', 'prix', 'etat', 'statut');
                },
                'post.user_info:id,firstname,lastname,username,avatar'
            ])
            ->get()
            ->map(function ($fav) {
                if ($fav->post) {
                    $fav->post->photos = collect($fav->post->photos)
                        ->map(fn($p) => asset('storage/' . $p));

                    if ($fav->post->user_info && $fav->post->user_info->avatar) {
                        $fav->post->user_info->avatar = asset('storage/' . $fav->post->user_info->avatar);
                    }
                }

                return [
                    'favorite_added_at' => $fav->created_at,
                    'post' => $fav->post
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $favorites
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/my-posts",
     *     tags={"Posts"},
     *     summary="List all posts created by authenticated user",
     *     description="Filters by month, year, type, status, or search keyword. Returns paginated results.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="month",
     *         in="query",
     *         description="Filter by month (1-12)",
     *         required=false,
     *         @OA\Schema(type="integer", example=11)
     *     ),
     *     @OA\Parameter(
     *         name="year",
     *         in="query",
     *         description="Filter by year",
     *         required=false,
     *         @OA\Schema(type="integer", example=2025)
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Type of post: annonce or vente",
     *         required=false,
     *         @OA\Schema(type="string", example="annonce")
     *     ),
     *     @OA\Parameter(
     *         name="statut",
     *         in="query",
     *         description="Status of the post",
     *         required=false,
     *         @OA\Schema(type="string", example="vendu")
     *     ),
     *     @OA\Parameter(
     *         name="key",
     *         in="query",
     *         description="Search keyword for title or description",
     *         required=false,
     *         @OA\Schema(type="string", example="iphone")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of user's posts",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(type="object")
     *             ),
     *             @OA\Property(
     *                 property="pagination",
     *                 type="object",
     *                 @OA\Property(property="currentPage", type="integer", example=1),
     *                 @OA\Property(property="lastPage", type="integer", example=5),
     *                 @OA\Property(property="nextPageUrl", type="string", example=null),
     *                 @OA\Property(property="previousPageUrl", type="string", example=null),
     *                 @OA\Property(property="totalItems", type="integer", example=82)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function MyPosts(Request $request)
    {
        $user = $request->user();

        $month  = $request->input('month');
        $year   = $request->input('year');
        $type   = $request->get('type', "annonce");
        $statut = $request->input('statut');
        $key    = $request->input('key');

        $query = posts::where("id_user", $user->id);

        if ($key) {
            $query->where(function($q) use ($key) {
                $q->where("titre", "LIKE", "%{$key}%")
                ->orWhere("description", "LIKE", "%{$key}%");
            });
        }

        if ($type !== "annonce") {
            $type = "vente";
        }

        if ($type === "vente") {
            $query->where('statut', '!=', 'vente');

            if ($month && $year) {
                $query->whereYear('sell_at', $year)
                    ->whereMonth('sell_at', $month);
            }

            $query->orderBy('sell_at', 'desc');
        } else {
            if ($month && $year) {
                $query->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month);
            }

            $query->orderBy('created_at', 'desc');
        }

        if (!empty($statut)) {
            switch ($statut) {
                case 'validation':
                    $query->where('statut', 'validation');
                    break;

                case 'vente':
                    $query->where(function($q) {
                        $q->where('statut', 'vente')
                        ->orWhere(function($sub) {
                            $sub->where('verified_at', '!=', null)
                                ->where('sell_at', null)
                                ->whereHas('user_info', function($u) {
                                    $u->where('voyage_mode', 0);
                                });
                        });
                    });
                    break;

                case 'vendu':
                    $query->where('statut', 'vendu')
                        ->orWhereNotNull('sell_at');
                    break;

                case 'livraison':
                case 'livré':
                case 'refusé':
                case 'préparation':
                case 'en cours de livraison':
                case 'ramassée':
                case 'retourné':
                    $query->where('statut', $statut);
                    break;

                case 'en voyage':
                    $query->where(function($q) {
                        $q->where('statut', 'en voyage')
                        ->orWhere(function($sub) {
                            $sub->whereHas('user_info', function($u) {
                                    $u->where('voyage_mode', 1);
                                })
                                ->whereNotNull('verified_at')
                                ->whereNull('sell_at');
                        });
                    });
                    break;

                default:
                    $query->where('statut', 'like', "%{$statut}%");
                    break;
            }
        }

        $posts = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $posts->items(),
            'pagination' => [
                'currentPage' => $posts->currentPage(),
                'lastPage' => $posts->lastPage(),
                'nextPageUrl' => $posts->nextPageUrl(),
                'previousPageUrl' => $posts->previousPageUrl(),
                'totalItems' => $posts->total(),
            ]
        ]);
    }





}
