<?php

namespace App\Http\Controllers;

use App\Models\notifications;
use App\Models\posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\ListGouvernorat;

class PostsController extends Controller
{
    use ListGouvernorat;

    /**
     * @SWG\Get(
     *     path="/api/posts",
     *     summary="Get a list of posts",
     *     tags={"Posts"},
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Invalid request")
     * )
     */
    public function list_post()
    {
        $post = posts::with('categorie_info')->get();
        return response()->json(
            [
                'success' => true,
                'data' => $post
            ]
        );
    }


    public function liste_publications(Request $request)
    {
        return view("Admin.publications.index");
    }

    public function liste_publications_supprimer(){
        $deleted = "oui" ;
        return view("Admin.publications.index", compact("deleted"));
    }


    public function liste_publications_signaler(){
        return view("Admin.publications.signalements");
    }

    public function liste_signalement_publications($id_post){
        $post = posts::find($id_post);
        if($post){
            return view("Admin.publications.liste-signalement")->with("post", $post);
        }else{
            return redirect()->route('post_signalers');
        }

    }


    public function details_publication(Request $request)
    {
        $statut = $request->get("statut") ?? "";
        $id = $request->id;
        $post = posts::find($id);
        if (!$post) {
            return redirect("/admin/publications");
        }
        if ($statut == "unread") {
            notifications::where("id_post", $id)->where("destination", "admin")->update(
                [
                    "statut" => "read"
                ]
            );
        }
        return view("Admin.publications.details")->with("post", $post);
    }




    public function details_post($id)
    {
        try {
            $post = posts::findOrFail($id);
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Le post a été trouver',
                    'post' => $post,
                    'category' => $post->categorie_info
                ]
            );
        } catch (\Exception $exception) {
            return response()->json(
                [
                    'success' => false,
                    'message' =>  "Impossible de trouver le post"
                ]
            );
        }
    }





    public function create_post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titre'    => 'required|string',
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

        //upload image
        if ($request->hasFile('photos')) {
            $images = $request->file('photo');
            $images = [];
            foreach ($images as $image) {
                $image = $this->upload_trait($image);
                array_push($images, $image);
            }
        }
        $post = new posts();
        $post->titre  = $request->input("titre");
        $post->description   = $request->input("description");
        $post->ville     = $request->input("ville");
        $post->prix       = $request->input("prix");
        $post->id_user     = Auth::User()->id;
        $post->photos = json_encode($images);
        $post->gouvernorat = $request->input("gouvernorat");
        $post->id_categorie  = $request->input("categorie");
        $post->save();

        return response()->json(
            [
                "success" => true,
                "message" => "Post ajouté avec succés",
                "data" => $post
            ]
        );
    }




    public function update_post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titre'    => 'required|string',
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
        $post->titre  = $request->input("titre");
        $post->description   = $request->input("description");
        $post->ville     = $request->input("ville");
        $post->prix       = $request->input("prix");
        $post->user_id      = Auth::User()->id;
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
            //foreach image to delete
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
