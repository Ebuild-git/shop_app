<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\favoris;
use App\Models\posts;
use App\Models\categories;
use App\Models\sous_categories;
use App\Models\regions;
use App\Models\configurations;
use App\Models\{notifications, History_change_price};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Events\AdminEvent;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\signalements;


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
     *     path="/api/favorites/count",
     *     summary="Count user favorite items",
     *     description="Returns the total number of favorite items for the authenticated user",
     *     tags={"Favorites"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Favorites count retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="count", type="integer", example=5)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function countFavorites(Request $request)
    {
        $userId = $request->user()->id;

        $count = favoris::where('id_user', $userId)->count();

        return response()->json([
            'success' => true,
            'count' => $count
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

        $query = posts::with("sous_categorie_info.categorie")->where("id_user", $user->id);

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

        $posts = $query->get();

        $posts = $posts->map(function($post) {
            $postData = $post->toArray();

            if (!empty($postData['photos'])) {
                $photos = $postData['photos'];

                if (is_array($photos)) {
                    $postData['photos'] = array_map(function($photo) {
                        $cleanPath = ltrim($photo, '/');
                        return asset('storage/' . $cleanPath);
                    }, $photos);
                }
            }
            if (!empty($postData['sous_categorie_info']['categorie'])) {
                if (!empty($postData['sous_categorie_info']['categorie']['icon'])) {
                    $iconPath = $postData['sous_categorie_info']['categorie']['icon'];
                    $cleanIconPath = ltrim($iconPath, '/');
                    $postData['sous_categorie_info']['categorie']['icon'] = asset('storage/' . $cleanIconPath);
                }

                if (!empty($postData['sous_categorie_info']['categorie']['small_icon'])) {
                    $smallIconPath = $postData['sous_categorie_info']['categorie']['small_icon'];
                    $cleanSmallIconPath = ltrim($smallIconPath, '/');
                    $postData['sous_categorie_info']['categorie']['small_icon'] = asset('storage/' . $cleanSmallIconPath);
                }
            }
            return $postData;
        });

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/my-purchases",
     *     tags={"Posts"},
     *     summary="Liste des achats de l'utilisateur authentifié",
     *     description="Retourne une liste paginée des posts achetés par l'utilisateur connecté, avec option de filtrage par mois et année.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="month",
     *         in="query",
     *         required=false,
     *         description="Filtrer par mois (1-12)",
     *         @OA\Schema(type="integer", example=11)
     *     ),
     *     @OA\Parameter(
     *         name="year",
     *         in="query",
     *         required=false,
     *         description="Filtrer par année",
     *         @OA\Schema(type="integer", example=2025)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Liste paginée des achats",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="total", type="integer", example=5),
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="last_page", type="integer", example=1),
     *             @OA\Property(property="next_page_url", type="string", nullable=true, example=null),
     *             @OA\Property(property="prev_page_url", type="string", nullable=true, example=null),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=10),
     *                     @OA\Property(property="titre", type="string", example="Macbook Pro 2021"),
     *                     @OA\Property(
     *                         property="photos",
     *                         type="array",
     *                         @OA\Items(type="string", example="http://127.0.0.1:8000/storage/products/mac1.jpg")
     *                     ),
     *                     @OA\Property(property="id_sous_categorie", type="integer", example=3),
     *                     @OA\Property(property="id_user", type="integer", example=7),
     *                     @OA\Property(property="statut", type="string", example="livré"),
     *                     @OA\Property(property="prix", type="number", format="float", example=3200),
     *                     @OA\Property(property="sell_at", type="string", format="date-time", example="2025-02-10 16:41:22")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Token manquant ou invalide"
     *     )
     * )
     */
    public function MesAchats(Request $request)
    {
        $userId = $request->user()->id;

        $month = $request->input('month');
        $year  = $request->input('year');

        $query = posts::with("sous_categorie_info.categorie")->where("id_user_buy", $userId)
            ->select("id", "titre", "photos", "id_sous_categorie", "id_user",
                    "statut", "prix", "sell_at")
            ->orderBy('sell_at', 'desc');

        if ($month && $year) {
            $query->whereYear('sell_at', $year)
                ->whereMonth('sell_at', $month);
        }

        $achats = $query->get();

        $achats = $achats->map(function ($post) {
            $postData = $post->toArray();

            if (!empty($postData['photos'])) {
                $photos = $postData['photos'];
                if (is_array($photos)) {
                    $postData['photos'] = array_map(function($photo) {
                        $cleanPath = ltrim($photo, '/');
                        return asset('storage/' . $cleanPath);
                    }, $photos);
                }
            }

            if (!empty($postData['sous_categorie_info']['categorie'])) {
                if (!empty($postData['sous_categorie_info']['categorie']['icon'])) {
                    $iconPath = $postData['sous_categorie_info']['categorie']['icon'];
                    $cleanIconPath = ltrim($iconPath, '/');
                    $postData['sous_categorie_info']['categorie']['icon'] = asset('storage/' . $cleanIconPath);
                }
                if (!empty($postData['sous_categorie_info']['categorie']['small_icon'])) {
                    $smallIconPath = $postData['sous_categorie_info']['categorie']['small_icon'];
                    $cleanSmallIconPath = ltrim($smallIconPath, '/');
                    $postData['sous_categorie_info']['categorie']['small_icon'] = asset('storage/' . $cleanSmallIconPath);
                }
            }

            return $postData;
        });

        return response()->json([
            'success' => true,
            'data' => $achats
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/posts/create",
     *     tags={"Posts"},
     *     summary="Create a new post",
     *     description="Creates a new post for the authenticated user. 'etat' must be one of: 'Neuf avec étiquettes','Neuf sans étiquettes','Très bon état','Bon état','Usé'. Include required and optional properties in 'proprietes'.",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"titre","etat","id_region","id_sous_categorie","prix"},
     *
     *             @OA\Property(property="titre", type="string", example="Superb Jacket"),
     *             @OA\Property(property="description", type="string", nullable=true, example="A stylish leather jacket"),
     *             @OA\Property(
     *                 property="etat",
     *                 type="string",
     *                 enum={"Neuf avec étiquettes","Neuf sans étiquettes","Très bon état","Bon état","Usé"},
     *                 example="Neuf avec étiquettes"
     *             ),
     *             @OA\Property(property="id_region", type="integer", example=1),
     *             @OA\Property(property="id_sous_categorie", type="integer", example=5),
     *             @OA\Property(property="prix", type="number", format="float", example=600),
     *             @OA\Property(property="prix_achat", type="number", format="float", nullable=true, example=400),
     *
     *             @OA\Property(property="photos1", type="string", format="binary"),
     *             @OA\Property(property="photos2", type="string", format="binary", nullable=true),
     *             @OA\Property(property="photos3", type="string", format="binary", nullable=true),
     *             @OA\Property(property="photos4", type="string", format="binary", nullable=true),
     *             @OA\Property(property="photos5", type="string", format="binary", nullable=true),
     *
     *             @OA\Property(
     *                 property="proprietes",
     *                 type="object",
     *                 example={
     *                     "Couleur": "#FF0000",
     *                     "Taille": "M",
     *                     "Marque": "Nike"
     *                 },
     *                 description="Key-value object for all required and optional properties"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Post created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Post created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=10),
     *                 @OA\Property(property="titre", type="string", example="Superb Jacket"),
     *                 @OA\Property(property="description", type="string", example="A stylish leather jacket"),
     *                 @OA\Property(property="etat", type="string", example="Neuf avec étiquettes"),
     *                 @OA\Property(property="prix", type="number", format="float", example=600),
     *                 @OA\Property(property="prix_achat", type="number", format="float", example=400, nullable=true),
     *                 @OA\Property(property="id_region", type="integer", example=1),
     *                 @OA\Property(property="id_sous_categorie", type="integer", example=5),
     *                 @OA\Property(property="id_user", type="integer", example=2),
     *                 @OA\Property(
     *                     property="proprietes",
     *                     type="object",
     *                     example={"Couleur":"#FF0000","Taille":"M","Marque":"Nike"}
     *                 ),
     *                 @OA\Property(
     *                     property="photos",
     *                     type="array",
     *                     @OA\Items(type="string", example="http://localhost/storage/uploads/posts/photo1.jpg")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors or other preconditions not met",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You must upload an image of your national identity card before publishing a post!"),
     *             @OA\Property(property="errors", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        if (!$user->cin_img) {
            return response()->json([
                'success' => false,
                'message' => "You must upload an image of your national identity card before publishing a post!"
            ], 422);
        }

        if (!$user->cin_approved) {
            return response()->json([
                'success' => false,
                'message' => "Your national identity card is being verified. You will receive a notification once it is approved."
            ], 422);
        }

        $subcategory = sous_categories::find($request->id_sous_categorie);
        if (!$subcategory) {
            return response()->json(['success' => false, 'message' => 'Invalid subcategory'], 422);
        }

        $category = $subcategory->categorie;

        $rules = [
            'titre' => 'required|min:2',
            'description' => 'string|nullable',
            'etat' => 'required|string',
            'id_region' => 'required|integer|exists:regions,id',
            'id_sous_categorie' => 'required|integer|exists:sous_categories,id',
            'prix' => 'required|numeric|min:50',
            'prix_achat' => 'nullable|numeric|min:50',
            'photos1' => 'nullable|file|image',
            'photos2' => 'nullable|file|image',
            'photos3' => 'nullable|file|image',
            'photos4' => 'nullable|file|image',
            'photos5' => 'nullable|file|image',
            'proprietes' => 'array',
        ];

        $allProps = json_decode($subcategory->required ?? '[]', true);
        if (is_array($allProps)) {
            foreach ($allProps as $item) {
                $prop = DB::table('proprietes')->where('id', $item['id'])->value('nom');
                if (!$prop) continue;

                if (($item['required'] ?? 'Non') === 'Oui') {
                    if ($prop === 'Couleur') {
                        $rules["proprietes.$prop"] = 'required|string';
                    } else {
                        $rules["proprietes.$prop"] = 'required';
                    }
                } else {
                    if ($prop === 'Couleur') {
                        $rules["proprietes.$prop"] = 'nullable|string';
                    } else {
                        $rules["proprietes.$prop"] = 'nullable';
                    }
                }
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        if ($category->luxury && $validated['prix'] < 800) {
            return response()->json([
                'success' => false,
                'message' => "The sale price must exceed 800 DH to be added to the LUXURY category"
            ], 422);
        }

        if (!$category->luxury && $validated['prix'] >= 800) {
            return response()->json([
                'success' => false,
                'message' => "The sale price must be less than 800 DH for the non-luxury version of this category."
            ], 422);
        }

        $photos = [];
        foreach (['photos1', 'photos2', 'photos3', 'photos4', 'photos5'] as $p) {
            if ($request->hasFile($p)) {
                $photos[] = $request->file($p)->store('uploads/posts', 'public');
            }
        }

        if (count($photos) < 3) {
            return response()->json([
                'success' => false,
                'message' => "You must add at least 3 photos!"
            ], 422);
        }

        $config = configurations::first();

        $post = new posts();
        $post->photos = $photos;
        $post->titre = $validated['titre'];
        $post->description = $validated['description'] ?? null;
        $post->etat = $validated['etat'];
        $post->id_region = $validated['id_region'];
        $post->id_sous_categorie = $validated['id_sous_categorie'];
        $post->prix = $validated['prix'];
        $post->prix_achat = $validated['prix_achat'] ?? null;
        $post->id_user = $user->id;

        $post->proprietes = $validated['proprietes'] ?? [];

        if ($config->valider_publication == 0) {
            $post->verified_at = now();
            $post->statut = 'vente';
        }

        $post->save();

        event(new AdminEvent('Un post a été créé avec succès.'));

        $notification = new notifications();
        $notification->type = "new_post";
        $notification->titre = $user->username . " vient de publier un article ";
        $notification->url = "/admin/publication/" . $post->id . "/view";
        $notification->message = $post->titre;
        $notification->id_post = $post->id;
        $notification->id_user = $user->id;
        $notification->destination = "admin";
        $notification->save();

        // Return full photo URLs
        $post->photos = array_map(fn($p) => asset('storage/' . $p), $post->photos);

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => $post
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $post = posts::find($id);
        if (!$post) {
            return response()->json(['success' => false, 'message' => 'Post not found'], 404);
        }

        if ($post->id_user !== $user->id) {
            return response()->json(['success' => false, 'message' => 'You are not allowed to update this post'], 403);
        }

        if (!$user->cin_img) {
            return response()->json([
                'success' => false,
                'message' => "You must upload an image of your national identity card before updating a post!"
            ], 422);
        }

        if (!$user->cin_approved) {
            return response()->json([
                'success' => false,
                'message' => "Your national identity card is being verified. You will receive a notification once it is approved."
            ], 422);
        }

        $subcategory = sous_categories::find($request->id_sous_categorie ?? $post->id_sous_categorie);
        if (!$subcategory) {
            return response()->json(['success' => false, 'message' => 'Invalid subcategory'], 422);
        }

        $category = $subcategory->categorie;

        $rules = [
            'titre' => 'required|min:2',
            'description' => 'string|nullable',
            'etat' => 'required|string|in:Neuf avec étiquettes,Neuf sans étiquettes,Très bon état,Bon état,Usé',
            'id_region' => 'required|integer|exists:regions,id',
            'id_sous_categorie' => 'required|integer|exists:sous_categories,id',
            'prix' => 'required|numeric|min:50',
            'prix_achat' => 'nullable|numeric|min:50',
            'photos1' => 'nullable|file|image',
            'photos2' => 'nullable|file|image',
            'photos3' => 'nullable|file|image',
            'photos4' => 'nullable|file|image',
            'photos5' => 'nullable|file|image',
            'proprietes' => 'array',
        ];

        // Handle required + optional properties
        $allProps = json_decode($subcategory->required ?? '[]', true);
        if (is_array($allProps)) {
            foreach ($allProps as $item) {
                $prop = DB::table('proprietes')->where('id', $item['id'])->value('nom');
                if (!$prop) continue;

                if (($item['required'] ?? 'Non') === 'Oui') {
                    if ($prop === 'Couleur') {
                        $rules["proprietes.$prop"] = 'required|string|regex:/^#[0-9A-Fa-f]{6}$/';
                    } else {
                        $rules["proprietes.$prop"] = 'required';
                    }
                } else {
                    if ($prop === 'Couleur') {
                        $rules["proprietes.$prop"] = 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/';
                    } else {
                        $rules["proprietes.$prop"] = 'nullable';
                    }
                }
            }
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        // Category price rules
        if ($category->luxury && $validated['prix'] < 800) {
            return response()->json([
                'success' => false,
                'message' => "The sale price must exceed 800 DH to be added to the LUXURY category"
            ], 422);
        }

        if (!$category->luxury && $validated['prix'] >= 800) {
            return response()->json([
                'success' => false,
                'message' => "The sale price must be less than 800 DH for the non-luxury version of this category."
            ], 422);
        }

        // Handle photos: replace only the ones sent
        $photos = $post->photos ?? [];
        foreach (['photos1', 'photos2', 'photos3', 'photos4', 'photos5'] as $index => $p) {
            if ($request->hasFile($p)) {
                $photos[$index] = $request->file($p)->store('uploads/posts', 'public');
            }
        }

        if (count($photos) < 3) {
            return response()->json([
                'success' => false,
                'message' => "You must have at least 3 photos!"
            ], 422);
        }

        $config = configurations::first();

        // Update post
        $post->titre = $validated['titre'];
        $post->description = $validated['description'] ?? $post->description;
        $post->etat = $validated['etat'];
        $post->id_region = $validated['id_region'];
        $post->id_sous_categorie = $validated['id_sous_categorie'];
        $post->prix = $validated['prix'];
        $post->prix_achat = $validated['prix_achat'] ?? $post->prix_achat;
        $post->proprietes = $validated['proprietes'] ?? $post->proprietes;
        $post->photos = $photos;

        if ($config->valider_publication == 0) {
            $post->verified_at = $post->verified_at ?? now();
            $post->statut = 'vente';
        }

        $post->save();

        // Return full photo URLs
        $post->photos = array_map(fn($p) => asset('storage/' . $p), $post->photos);

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully',
            'data' => $post
        ], 200);
    }


    /**
     * @OA\Post(
     *     path="/api/posts/{id}/reduce-price",
     *     tags={"Posts"},
     *     summary="Reduce the price of a post",
     *     description="Allows the authenticated user to decrease the price of their post. The price can only be reduced once per week. Price increases are not allowed.",
     *
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the post whose price will be reduced",
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"prix"},
     *             @OA\Property(
     *                 property="prix",
     *                 type="number",
     *                 format="float",
     *                 example=120,
     *                 description="New reduced price. Must be less than the current price."
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Price successfully reduced",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Price successfully reduced from 150 to 120."),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=15),
     *                 @OA\Property(property="old_price", type="number", example=150),
     *                 @OA\Property(property="new_price", type="number", example=120)
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
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Post not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Post not found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or price cannot be increased",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You cannot increase the price. Only reductions are allowed.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=429,
     *         description="Weekly price change limit reached",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You can change the price again in 3 day(s) and 5 hour(s).")
     *         )
     *     )
     * )
     */
    public function reducePrice(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $post = posts::where('id', $id)
            ->where('id_user', $user->id)
            ->first();

        if (!$post) {
            return response()->json(['success' => false, 'message' => 'Post not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'prix' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $newPrice = $request->prix;
        $oldPrice = $post->prix;

        if ($newPrice == $oldPrice) {
            return response()->json([
                'success' => false,
                'message' => "The new price must be different from the old price."
            ], 422);
        }

        if ($newPrice > $oldPrice) {
            return response()->json([
                'success' => false,
                'message' => "You cannot increase the price. Only reductions are allowed."
            ], 422);
        }

        $lastChange = History_change_price::where('id_post', $post->id)
            ->orderBy('created_at', 'desc')
            ->first();

        $now = Carbon::now();

        if ($lastChange && $lastChange->created_at->greaterThan($now->subWeek())) {

            $nextChange = $lastChange->created_at->addWeek();
            $days = $now->diffInDays($nextChange);
            $hours = $now->copy()->addDays($days)->diffInHours($nextChange);

            return response()->json([
                'success' => false,
                'message' => "You can change the price again in $days day(s) and $hours hour(s)."
            ], 429);
        }

        if ($post->old_prix === null) {
            $post->old_prix = $oldPrice;
        }

        $post->prix = $newPrice;
        $post->updated_price_at = now();
        $post->save();

        History_change_price::create([
            'id_post' => $post->id,
            'old_price' => $oldPrice,
            'new_price' => $newPrice
        ]);

        event(new AdminEvent('Un utilisateur a réduit le prix de son article.'));

        $notification = new notifications();
        $notification->type = "photo";
        $notification->titre = $user->username . " a réduit le prix d’un article.";
        $notification->url = "/admin/publication/" . $post->id . "/view";
        $notification->message = "Prix mis à jour de {$oldPrice} à {$newPrice}.";
        $notification->id_user = $user->id;
        $notification->destination = "admin";
        $notification->save();

        return response()->json([
            'success' => true,
            'message' => "Price successfully reduced from $oldPrice to $newPrice.",
            'data' => [
                'id' => $post->id,
                'old_price' => $oldPrice,
                'new_price' => $newPrice,
            ]
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/posts/{id}/report",
     *     tags={"Posts"},
     *     summary="Report a post",
     *     description="Allows a user to report a post.<br><br>
     *     <strong>Allowed report types:</strong><br>
     *     • Annonce de produits interdits ou illégaux<br>
     *     • Annonce multiple du même article<br>
     *     • Autres violations des politiques du site<br>
     *     • Contenu inapproprié<br>
     *     • Description trompeuse de l'état de l'article<br>
     *     • Fraude ou activité suspecte<br>
     *     • Information incorrecte sur la taille, la couleur, etc.<br>
     *     • Photos floues ou de mauvaise qualité<br>
     *     • Prix excessif pour le produit mis en vente<br>
     *     • Produit contrefait ou non authentique<br>
     *     • Publicité non autorisée ou spam<br>
     *     • Violation des droits d'auteur ou de la propriété intellectuelle",
     *
     *     security={{ "bearerAuth":{} }},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the post to report",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"type","message"},
     *             @OA\Property(
     *                 property="type",
     *                 type="string",
     *                 description="Type of report",
     *                 enum={
     *                     "Annonce de produits interdits ou illégaux",
     *                     "Annonce multiple du même article",
     *                     "Autres violations des politiques du site",
     *                     "Contenu inapproprié",
     *                     "Description trompeuse de l'état de l'article",
     *                     "Fraude ou activité suspecte",
     *                     "Information incorrecte sur la taille, la couleur, etc.",
     *                     "Photos floues ou de mauvaise qualité",
     *                     "Prix excessif pour le produit mis en vente",
     *                     "Produit contrefait ou non authentique",
     *                     "Publicité non autorisée ou spam",
     *                     "Violation des droits d'auteur ou de la propriété intellectuelle"
     *                 },
     *                 example="Contenu inapproprié"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 minLength=15,
     *                 description="Message describing the reason for the report (minimum 15 characters).",
     *                 example="This post contains inappropriate content."
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Report submitted successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Report already submitted"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function report(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $post = posts::find($id);
        if (!$post) {
            return response()->json(['success' => false, 'message' => 'Post not found'], 404);
        }

        $rules = [
            'type' => 'required|string',
            'message' => 'required|string|min:15',
        ];

        $validated = $request->validate($rules);

        if (signalements::where('id_user_make', $user->id)
            ->where('id_post', $post->id)
            ->exists()
        ) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reported this post.'
            ], 409);
        }

        $signalement = new signalements();
        $signalement->id_post = $post->id;
        $signalement->id_user_make = $user->id;
        $signalement->message = $validated['message'];
        $signalement->type = $validated['type'];
        $signalement->save();

        event(new AdminEvent('Un post a été signalé.'));

        $notification = new notifications();
        $notification->type = "report";
        $notification->titre = $user->username . " a signalé une publication";
        $notification->url = "/admin/publication/" . $post->id . "/view";
        $notification->message = "Raison : " . $validated['type'] . " — " . $validated['message'];
        $notification->id_post = $post->id;
        $notification->id_user = $user->id;
        $notification->destination = "admin";
        $notification->save();

        return response()->json([
            'success' => true,
            'message' => 'Your report has been submitted successfully.',
            'data' => [
                'post_id' => $post->id,
                'type' => $validated['type'],
                'message' => $validated['message'],
            ]
        ], 201);
    }



}
