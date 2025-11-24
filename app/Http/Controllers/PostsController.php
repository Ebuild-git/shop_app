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
     *     description="Returns a paginated list of posts including photos as full URLs",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of posts",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="data", type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=30),
     *                         @OA\Property(property="titre", type="string", example="Manteau old style"),
     *                         @OA\Property(property="description", type="string", example="Manteau camel Mango"),
     *                         @OA\Property(
     *                             property="photos",
     *                             type="array",
     *                             @OA\Items(type="string", example="http://127.0.0.1:8000/storage/uploads/posts/TqhzM91f7GYgu5V6NwiYYNi1hKZEk9TlA26dZJ8a.webp")
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(property="total", type="integer", example=150),
     *                 @OA\Property(property="per_page", type="integer", example=50),
     *                 @OA\Property(property="last_page", type="integer", example=3)
     *             )
     *         )
     *     )
     * )
     */
    public function list_post()
    {
        $posts = posts::paginate(20);

        $posts->getCollection()->transform(function ($post) {
            $post->photos = collect($post->photos)->map(fn($photo) => asset('storage/' . $photo));
            return $post;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'current_page' => $posts->currentPage(),
                'data' => $posts->items(),
                'total' => $posts->total(),
                'per_page' => $posts->perPage(),
                'last_page' => $posts->lastPage(),
            ]
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
            ->with("post", $post);
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
            $post = posts::with('sous_categorie_info.categorie')->findOrFail($id);

            $post->photos = collect($post->photos)->map(fn($photo) => asset('storage/' . $photo));

            if ($post->sous_categorie_info && $post->sous_categorie_info->categorie) {
                $categorie = $post->sous_categorie_info->categorie;
                $categorie->icon = $categorie->icon ? asset('storage/' . $categorie->icon) : null;
                $categorie->small_icon = $categorie->small_icon ? asset('storage/' . $categorie->small_icon) : null;
            }

            return response()->json([
                'success' => true,
                'post' => $post,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Impossible de trouver le post"
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
            $images = $request->file('photo');
            $images = [];
            foreach ($images as $image) {
                $image = $this->upload_trait($image);
                array_push($images, $image);
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
            $images = $request->file('photo');
            $images = [];
            foreach ($images as $image) {
                $image = $this->upload_trait($image);
                array_push($images, $image);
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
