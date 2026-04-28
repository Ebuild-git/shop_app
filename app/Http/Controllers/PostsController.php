<?php

namespace App\Http\Controllers;

use App\Events\UserEvent;
use App\Models\motifs;
use App\Models\notifications;
use App\Models\posts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\ListGouvernorat;

class PostsController extends Controller
{
    use ListGouvernorat;

    /**
     * @OA\Get(
     *     path="/api/posts",
     *     tags={"Posts"},
     *     summary="List paginated posts",
     *     description="Returns a paginated list of posts with full photo URLs. Supports filtering by luxury, etat, proprietes, and full-text search across title, category, and sub-category.",
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination (20 results per page)",
     *         required=false,
     *         @OA\Schema(type="integer", default=1, example=1)
     *     ),
     *
     *     @OA\Parameter(
     *         name="luxury",
     *         in="query",
     *         description="Filter posts by whether their category is luxury or not. Pass true for luxury items, false for non-luxury.",
     *         required=false,
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *
     *     @OA\Parameter(
     *         name="etat",
     *         in="query",
     *         description="Filter posts by condition (etat). Must match exactly one of the stored values.",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"Neuf avec étiquettes", "Neuf sans étiquettes", "Très bon état", "Bon état", "État satisfaisant"},
     *             example="Neuf avec étiquettes"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Full-text search across post title (titre), sub-category name (titre, title_en, title_ar), and category name (titre, title_en, title_ar).",
     *         required=false,
     *         @OA\Schema(type="string", example="caftan")
     *     ),
     *
     *     @OA\Parameter(
     *         name="proprietes[Taille]",
     *         in="query",
     *         description="Filter by the 'Taille' property inside the proprietes JSON column. Partial match supported.",
     *         required=false,
     *         @OA\Schema(type="string", example="4XL")
     *     ),
     *
     *     @OA\Parameter(
     *         name="proprietes[Article pour]",
     *         in="query",
     *         description="Filter by the 'Article pour' property inside the proprietes JSON column. Partial match supported.",
     *         required=false,
     *         @OA\Schema(type="string", example="Femme")
     *     ),
     *
     *     @OA\Parameter(
     *         name="proprietes[Couleur]",
     *         in="query",
     *         description="Filter by the 'Couleur' property inside the proprietes JSON column. Partial match supported.",
     *         required=false,
     *         @OA\Schema(type="string", example="#000000")
     *     ),
     *
     *     @OA\Parameter(
     *         name="proprietes[Poids]",
     *         in="query",
     *         description="Filter by the 'Poids' property inside the proprietes JSON column. Partial match supported.",
     *         required=false,
     *         @OA\Schema(type="string", example="1")
     *     ),
     *
     *     @OA\Parameter(
     *         name="proprietes[Eventuels défauts]",
     *         in="query",
     *         description="Filter by the 'Eventuels défauts' property inside the proprietes JSON column. Partial match supported.",
     *         required=false,
     *         @OA\Schema(type="string", example="cc")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of posts",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=165),
     *                     @OA\Property(property="titre", type="string", example="Manteau old style"),
     *                     @OA\Property(property="description", type="string", nullable=true, example="Très beau manteau"),
     *                     @OA\Property(
     *                         property="photos",
     *                         type="array",
     *                         @OA\Items(type="string", example="http://127.0.0.1:8000/storage/uploads/posts/abc.webp")
     *                     ),
     *                     @OA\Property(property="id_region", type="integer", example=1),
     *                     @OA\Property(property="id_user", type="integer", example=19),
     *                     @OA\Property(property="id_sous_categorie", type="integer", example=1),
     *                     @OA\Property(
     *                         property="proprietes",
     *                         type="object",
     *                         description="Dynamic JSON object of product properties (keys and values vary by sub-category)",
     *                         example={"Taille": "4XL/50/18", "Article pour": "Femme", "Poids": "1"}
     *                     ),
     *                     @OA\Property(
     *                         property="etat",
     *                         type="string",
     *                         enum={"Neuf avec étiquettes", "Neuf sans étiquettes", "Très bon état", "Bon état", "État satisfaisant"},
     *                         example="Neuf avec étiquettes"
     *                     ),
     *                     @OA\Property(property="prix", type="number", format="float", example=107),
     *                     @OA\Property(property="old_prix", type="number", format="float", nullable=true, example=112),
     *                     @OA\Property(property="is_solder", type="boolean", example=true),
     *                     @OA\Property(property="statut", type="string", example="vente"),
     *                     @OA\Property(property="views", type="integer", example=1),
     *                     @OA\Property(property="verified_at", type="string", format="date-time", example="2026-04-16 09:59:20"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2026-04-16T09:59:20.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2026-04-16T09:59:27.000000Z"),
     *                     @OA\Property(
     *                         property="sous_categorie_info",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="titre", type="string", example="Caftan & Takchita"),
     *                         @OA\Property(property="title_en", type="string", nullable=true, example="Caftan & Takchita"),
     *                         @OA\Property(property="title_ar", type="string", nullable=true, example="القفطان والتكشيطة"),
     *                         @OA\Property(
     *                             property="categorie",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="titre", type="string", example="Fashion & Vêtements"),
     *                             @OA\Property(property="title_en", type="string", nullable=true, example="Fashion & Clothing"),
     *                             @OA\Property(property="title_ar", type="string", nullable=true, example="الموضة والملابس"),
     *                             @OA\Property(property="luxury", type="boolean", example=false),
     *                             @OA\Property(property="icon", type="string", nullable=true, example="http://127.0.0.1:8000/storage/uploads/categories/icon.png"),
     *                             @OA\Property(property="small_icon", type="string", nullable=true, example="http://127.0.0.1:8000/storage/uploads/categories/small_icon.png")
     *                         )
     *                     ),
     *                     @OA\Property(
     *                         property="changements_prix",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=20),
     *                             @OA\Property(property="id_post", type="integer", example=165),
     *                             @OA\Property(property="old_price", type="string", example="105.00"),
     *                             @OA\Property(property="new_price", type="string", example="100.00"),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2026-04-16T09:59:27.000000Z")
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=3),
     *                 @OA\Property(property="per_page", type="integer", example=20),
     *                 @OA\Property(property="total", type="integer", example=43),
     *                 @OA\Property(property="has_more", type="boolean", example=true)
     *             )
     *         )
     *     )
     * )
     */
    public function list_post(Request $request)
    {
        $query = posts::with("sous_categorie_info.categorie")
            ->whereHas('user_info', function ($query) {
                $query->where('voyage_mode', 0);
            });

        if ($request->has('luxury')) {
            $luxuryValue = filter_var($request->luxury, FILTER_VALIDATE_BOOLEAN);
            $query->whereHas('sous_categorie_info.categorie', function ($q) use ($luxuryValue) {
                $q->where('luxury', $luxuryValue);
            });
        }

        if ($request->filled('proprietes')) {
            $proprietes = $request->proprietes; // e.g. ["Taille" => "4XL", "Article pour" => "Femme"]

            if (is_array($proprietes)) {
                foreach ($proprietes as $key => $value) {
                    if (!empty($value)) {
                        $query->whereRaw(
                            "JSON_UNQUOTE(JSON_EXTRACT(proprietes, ?)) LIKE ?",
                            ['$."' . $key . '"', "%{$value}%"]
                        );
                    }
                }
            }
        }

        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                ->orWhereHas('sous_categorie_info', function ($q2) use ($search) {
                    $q2->where('titre', 'like', "%{$search}%")
                    ->orWhere('title_en', 'like', "%{$search}%")
                    ->orWhere('title_ar', 'like', "%{$search}%")
                    ->orWhereHas('categorie', function ($q3) use ($search) {
                        $q3->where('titre', 'like', "%{$search}%")
                            ->orWhere('title_en', 'like', "%{$search}%")
                            ->orWhere('title_ar', 'like', "%{$search}%");
                    });
                });
            });
        }

        $query->orderBy('created_at', 'desc');

        $posts = $query->paginate(20);

        $posts->getCollection()->transform(function ($post) {
            $post->prix     = $post->getPrix();
            $post->old_prix = $post->getOldPrix();
            $post->is_solder = $post->getOldPrix() ? true : false;

            $postData = $post->toArray();

            if (!empty($postData['photos']) && is_array($postData['photos'])) {
                $postData['photos'] = array_map(function ($photo) {
                    return asset('storage/' . ltrim($photo, '/'));
                }, $postData['photos']);
            }

            if (!empty($postData['sous_categorie_info']['categorie'])) {
                $cat = &$postData['sous_categorie_info']['categorie'];

                if (!empty($cat['icon'])) {
                    $cat['icon'] = asset('storage/' . ltrim($cat['icon'], '/'));
                }
                if (!empty($cat['small_icon'])) {
                    $cat['small_icon'] = asset('storage/' . ltrim($cat['small_icon'], '/'));
                }
            }

            return $postData;
        });

        return response()->json([
            'success' => true,
            'data'    => $posts->items(),
            'meta'    => [
                'current_page' => $posts->currentPage(),
                'last_page'    => $posts->lastPage(),
                'per_page'     => $posts->perPage(),
                'total'        => $posts->total(),
                'has_more'     => $posts->hasMorePages(),
            ],
        ]);
    }

    public function liste_publications(Request $request)
    {
        return view("Admin.publications.index");
    }

    public function liste_publications_supprimer()
    {
        $deleted = "oui";
        return view("Admin.publications.index", compact("deleted"));
    }


    public function details_publication(Request $request)
    {
        $statut = $request->get("statut") ?? "";
        $id = $request->id;
        $post = posts::withTrashed()->find($id);
        if (!$post) {
            return redirect("/admin/publications")
                ->with("error", "Publication introuvable !");
        }
        if ($statut == "unread") {
            notifications::where("id_post", $id)->where("destination", "admin")->update(
                [
                    "statut" => "read"
                ]
            );
        }
        return view("Admin.publications.details")
            ->with("post", $post)
            ->with("signalements", $post->signalements()->with('auteur')->get());

    }

    /**
     * @OA\Get(
     *     path="/api/post/{id}",
     *     tags={"Posts"},
     *     summary="Get post details",
     *     description="Returns detailed information of a single post including photos and category info",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="post",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=30),
     *                 @OA\Property(property="titre", type="string", example="Manteau old style"),
     *                 @OA\Property(property="description", type="string", example="Manteau camel Mango"),
     *                 @OA\Property(
     *                     property="photos",
     *                     type="array",
     *                     @OA\Items(type="string", example="http://127.0.0.1:8000/storage/uploads/posts/TqhzM91f7GYgu5V6NwiYYNi1hKZEk9TlA26dZJ8a.webp")
     *                 ),
     *                 @OA\Property(property="proprietes", type="object",
     *                     example={"Marque": "Bershka", "Taille": "L/42/10", "Couleur": "#C19A6B", "Article pour": "Femme", "Eventuels défauts": "No"}
     *                 ),
     *                 @OA\Property(property="etat", type="string", example="Bon état"),
     *                 @OA\Property(property="prix", type="string", example="300.00"),
     *                 @OA\Property(property="sous_categorie_info", type="object",
     *                     @OA\Property(property="id", type="integer", example=10),
     *                     @OA\Property(property="titre", type="string", example="Manteau"),
     *                     @OA\Property(property="categorie", type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="titre", type="string", example="Fashion & Vêtements"),
     *                         @OA\Property(property="description", type="string", example="Fashion & Vêtements looollmm"),
     *                         @OA\Property(property="icon", type="string", example="http://127.0.0.1:8000/storage/uploads/categories/goCedMgqRvvQQ7xzuwNBgraFZSuuvWShzRCknYvP.png"),
     *                         @OA\Property(property="small_icon", type="string", example="http://127.0.0.1:8000/storage/uploads/categories/oE8n20szUVwCTh4pSCy8VXSP0KvTliyMBgX2kDxg.png")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Post not found")
     * )
     */
    public function details_post($id)
    {
        try {
            $post = posts::with([
                'sous_categorie_info.categorie',
                'user_info' => function ($q) {
                    $q->select(
                        'id',
                        'firstname',
                        'lastname',
                        'username',
                        'avatar',
                        'email',
                        'voyage_mode'
                    );
                }
            ])
                ->findOrFail($id);

            $post->photos = collect($post->photos)->map(
                fn($photo) => asset('storage/' . $photo)
            );

            if ($post->sous_categorie_info && $post->sous_categorie_info->categorie) {
                $categorie = $post->sous_categorie_info->categorie;
                $categorie->icon = $categorie->icon
                    ? asset('storage/' . $categorie->icon)
                    : null;
                $categorie->small_icon = $categorie->small_icon
                    ? asset('storage/' . $categorie->small_icon)
                    : null;
            }

            if ($post->user_info) {
                $user = $post->user_info;

                $user->avatar = $user->avatar
                    ? asset('storage/' . $user->avatar)
                    : null;

                $avis = $user->getReviewsAttribute->count();
                $averageRating = number_format(
                    $user->averageRating->average_rating ?? 1,
                    1
                );

                $totalSales = $user->total_sales->count();
                $validatedPosts = $user->ValidatedPosts->count();

                $user->stats = [
                    'avis' => $avis,
                    'average_rating' => $averageRating,
                    'total_sales' => $totalSales,
                    'total_annonces' => $validatedPosts,
                ];
            }

            $post->prix = $post->getPrix();
            $post->old_prix = $post->getOldPrix();

            return response()->json([
                'success' => true,
                'post' => $post,
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de trouver le post'
            ]);
        }
    }

    public function list_motifs(Request $request)
    {
        $id_post = $request->input('id_post' ?? null);
        if ($id_post) {
            $motifs = motifs::where("id_post", $id_post)
                ->where('id_user', Auth::user()->id)
                ->select("motif", "created_at")
                ->get()
                ->toArray();

            if ($motifs) {
                return response()->json(
                    [
                        'data' => $motifs,
                        'statut' => true
                    ]
                );
            } else {
                return response()->json(
                    [
                        'statut' => false
                    ]
                );
            }
        }
    }

    public function create_post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'required|string',
            'description' => 'required|string',
            'ville' => 'required|string',
            'prix' => 'required|numeric',
            'gouvernorat' => 'required|string',
            'categorie' => 'required|integer|exists:categories,id',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'photos' => 'required|max:10|min:1',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Veuillez verifier les champs !',
                "errors" => $validator->errors()
            ]);
        }

        if ($request->hasFile('photos')) {
            $uploadedFiles = $request->file('photos');
            $images = [];
            foreach ($uploadedFiles as $image) {
                $path = \App\Services\ImageService::uploadAndConvert($image, 'uploads/posts');
                $images[] = $path;
            }
        }
        $post = new posts();
        $post->titre = $request->input("titre");
        $post->description = $request->input("description");
        $post->ville = $request->input("ville");
        $post->prix = $request->input("prix");
        $post->id_user = Auth::User()->id;
        $post->photos = json_encode($images);
        $post->gouvernorat = $request->input("gouvernorat");
        $post->id_categorie = $request->input("categorie");
        $post->save();

        return response()->json(
            [
                "success" => true,
                "message" => "Post ajouté avec succés",
                "data" => $post
            ]
        );
    }


    public function username(Request $request)
    {
        $username = $request->input("username");
        if (is_null($username)) {
            return response()->json([
                'success' => false,
                'message' => 'Veuillez vous rassurer que le username a été entrer',
            ]);
        }
        $count = User::where('username', $username)->count();
        return response()->json([
            'success' => true,
            'message' => '',
            'total' => $count
        ]);
    }


    public function update_post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'required|string',
            'description' => 'required|string',
            'ville' => 'required|string',
            'prix' => 'required|numeric',
            'id_post' => 'required|integer|exists:posts,id',
            'gouvernorat' => 'required|string',
            'categorie' => 'required|integer|exists:categories,id',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'photos' => 'max:10|min:1',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Veuillez verifier les champs !',
                "errors" => $validator->errors()
            ]);
        }

        $post = posts::findOrFail($request->input("id_post"));
        $post->titre = $request->input("titre");
        $post->description = $request->input("description");
        $post->ville = $request->input("ville");
        $post->prix = $request->input("prix");
        $post->user_id = Auth::User()->id;
        $post->gouvernorat = $request->input("gouvernorat");
        $post->category_id = $request->input("categorie");

        if ($request->hasFile('photos')) {
            $uploadedFiles = $request->file('photos');
            $images = [];
            foreach ($uploadedFiles as $image) {
                $path = \App\Services\ImageService::uploadAndConvert($image, 'uploads/posts');
                $images[] = $path;
            }
        }
        $post->save();
        return response()->json(
            [
                "success" => true,
                "message" => "Post modifié avec succés",
                "data" => $post
            ]
        );
    }


    public function delete(Request $request)
    {
        try {
            $post = posts::findOrFail($request->input('id'));
            foreach ($post->photos as $img) {
                $this->delete_trait($img);
            }
            $post->delete();
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Le post a été supprimé'
                ]
            );
        } catch (\Exception $exception) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $exception->getCode()
                ]
            );
        }
    }
}
