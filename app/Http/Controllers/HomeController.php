<?php

namespace App\Http\Controllers;

use App\Events\AdminEvent;
use App\Events\MyEvent;
use App\Events\UserEvent;
use App\Mail\VerifyMail;
use App\Models\categories;
use App\Models\configurations;
use App\Models\favoris;
use App\Models\likes;
use App\Models\notifications;
use App\Models\posts;
use App\Models\regions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
        $categories = categories::all();
        $configuration = configurations::firstorCreate();
        $last_post = posts::join('sous_categories', 'posts.id_sous_categorie', '=', 'sous_categories.id')
            ->join('categories', 'sous_categories.id_categorie', '=', 'categories.id')
            ->where('categories.luxury', false)
            ->whereNull('posts.sell_at')
            ->orderByRaw('GREATEST(posts.created_at, posts.updated_price_at) DESC')
            ->select("posts.id", "posts.photos")
            ->Orderby("posts.id","Desc")
            ->take(12)
            ->get();

        $luxurys = posts::join('sous_categories', 'posts.id_sous_categorie', '=', 'sous_categories.id')
            ->join('categories', 'sous_categories.id_categorie', '=', 'categories.id')
            ->where('categories.luxury', true)
            ->whereNull('posts.sell_at')
            ->orderby("posts.created_at", "Desc")
            ->select("posts.id", "posts.photos")
            ->take(8)->get();
        return view("User.index", compact("categories", "configuration", "last_post", "luxurys"));
    }

    public function index_post(Request $request)
    {
        $id = $request->id ?? "";
        return view('User.post', compact("id"));
    }

    public function index_mes_post(Request $request)
    {
        $date_post = $request->input('date' ?? null);
        $statut = $request->input('statut' ?? null);
        $Query = posts::where("id_user", Auth::user()->id)->Orderby("id", "Desc");


        if ($date_post) {
            $date = $date_post . '-01';
            $Query->whereYear('Created_at', date('Y', strtotime($date)))
                ->whereMonth('Created_at', date('m', strtotime($date)));
        }


        if (!empty($statut)) {
            switch ($statut) {
                case 'validation':
                    $postsQuery = $Query->where('statut', "validation");
                    break;
                case 'vente':
                    $postsQuery = $Query->where('statut', "vente");
                    break;
                case 'vendu':
                    $postsQuery = $Query->where('statut', "vendu");
                    break;
                case 'livraison':
                    $postsQuery = $Query->where('statut', "livraison");
                    break;
                case 'livré':
                    $postsQuery = $Query->where('statut', "livré");
                    break;
            }
        }
        $posts =  $Query->paginate("30");
        return view('User.list_post')
            ->with("posts", $posts)
            ->with("date", $date_post)
            ->with("statut", $statut);
    }

    public function details_post($id)
    {
        $post = posts::find($id);
        if (!$post) {
            abort(404);
        }
        $user = $post->user_info;

        //si un user est connecter verifier si ce post est dans ses favoris
        if (Auth::check()) {
            $isFavorited =  favoris::where('id_post', $post->id)->where('id_user', Auth::user()->id)->exists();
            $isLiked = likes::where('id_post', $post->id)->where('id_user', Auth::user()->id)->exists();
        } else {
            $isFavorited = false;
            $isLiked = false;
        }

        $other_product = posts::where('id_sous_categorie', $post->id_sous_categorie)
            ->select("photos", "id")
            ->where("verified_at", '!=', null)
            ->inRandomOrder()
            ->take(16)
            ->get();
        return view('User.details')
            ->with("post", $post)
            ->with("user", $user)
            ->with("isFavorited", $isFavorited)
            ->with("isLiked", $isLiked)
            ->with("other_products", $other_product);
    }






    public function user_profile($id)
    {
        $user = User::find($id);
        $postsQuery = posts::where("id_user", $user->id);
        $posts = $postsQuery->get();
        return view('User.profile')->with("user", $user)->with('posts', $posts);
    }


    public function informations()
    {
        return view('User.infromations');
    }

    public function historiques()
    {
        $count = posts::where("id_user", Auth::user()->id)->count();
        return view('User.historiques', compact("count"));
    }

    public function list_proposition($id_post)
    {
        $post = posts::where('id', $id_post)->where("id_user", Auth::id())->first();
        if ($post) {
            return view("User.propositions", compact("post"));
        } else {
            echo "erro";
        }
    }







    public function inscription_post(Request $request)
    {
        $year = date('Y');
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', 'string', 'min:8'],
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'matricule' => 'nullable|mimes:jpg,png,jpeg,pdf|max:2048',
            'nom' => ['required', 'string'],
            'prenom' => ['required', 'string'],
            'adress' => ['nullable', 'string'],
            'telephone' => ['required', 'string', 'Max:15'],
            'username' => "string|unique:users,username",
            'genre' => 'required|in:female,male',
            'jour' => 'required|integer|between:1,31',
            'mois' => 'required|integer|between:1,12',
            'annee' => 'required|integer|between:1950,2024',
        ], [
            'required' => "Veuillez renseigner ce lien",
            'username.unique' => "Ce pseudo est déja utilisé",
            'email.unique' => "Cette adresse email est déja utilisé",
            "string" => "Veuillez entrer une valeur de type texte",
            "password.min" => "Votre mot de passe doit contenir minimun 8 caractères",
            "password.confirmed" => "Votre mot de passe ne correspond pas",
            "interger" => "Veuillez entrer une valeur de type entier",
            "in.genre" => "Veuillez choisir votre sexe",
            "mimes" => "Veuillez choisir un format de fichier valide",
            "image" => "Veuillez choisir une image valide",
            "max" => "Veuillez choisir un fichier de taille inférieur à 2Mo",
            "between" => "Veuillez choisir une date valide",

        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        $date = \Carbon\Carbon::createFromDate($request->annee, $request->mois, $request->jour);

        // Calculer l'âge avec précision
        $age = $date->diffInYears(\Carbon\Carbon::now());
        //l'age doit etres superieur a 13 ans return with information
        if ($age < 13) {
            return redirect()->back()->with("error", "L'âge minimal est de 13 ans")->withInput();
        }


        $config = configurations::first();
        $token = md5(time());

        $user = new User();
        $user->lastname = $request->nom;
        $user->email = $request->email;
        $user->firstname = $request->prenom;
        $user->password = Hash::make($request->password);
        $user->phone_number = $request->telephone;
        $user->birthdate = $date;
        $user->gender = $request->genre;
        $user->role = "user";
        $user->type = "user";
        $user->address = $request->adress;
        $user->username = $request->username;
        $user->ip_address = request()->ip();
        $user->remember_token = $token;

        if ($request->matricule) {
            $matricule = $request->matricule->store('uploads/documents', 'public');
            $user->type = "shop";
            $user->matricule = $matricule;
        }

        $user->photo_verified_at = now();
        $user->save();
        //donner le role user
        $user->assignRole('user');

        //envoi du mail avec le lien de validation
        Mail::to($user->email)->send(new VerifyMail($user, $token));

        return redirect("/connexion")->with("success", "Votre compte a été créé avec succès! Pour
    finaliser votre inscription, cliquez sur le lien de
    validation que nous vous avons envoyé par
    e-mail. Merci et bienvenue parmi nous !");
        //reset form
    }


    public function count_panier()
    {
        $cart = json_decode($_COOKIE['cart'] ?? '[]', true);
        $produits = [];
        $montant = 0;
        $html = '';
        foreach ($cart ?? [] as $item) {
            $produit = posts::find($item['id']);
            if ($produit) {
                $produits[] = [
                    'id' => $produit->id,
                    'titre' => $produit->titre,
                    'photo' => Storage::url($produit->photos[0] ?? ''),
                    'prix' => $produit->getPrix() . " DH",
                ];
                $montant += $produit->getPrix();

                $html .= '<div class="d-flex align-items-center justify-content-between br-bottom px-3 py-3">
                    <div class="cart_single d-flex align-items-center">
                        <div class="cart_selected_single_thumb">
                            <a href="#">
                                <img src="' . Storage::url($produit->photos[0] ?? '') . '" width="60" class="img-fluid"
                                    alt="" />
                            </a>
                        </div>
                        <div class="cart_single_caption pl-2">
                            <h4 class="product_title fs-sm ft-medium mb-0 lh-1">
                            ' . $produit->titre . '
                            </h4>
                            <h4 class="fs-md ft-medium mb-0 lh-1 color">
                            ' . $produit->getPrix() . ' DH
                            </h4>
                        </div>
                    </div>
                    <div class="fls_last">
                        <button class="close_slide gray" type="button" onclick="remove_to_card(' . $produit->id . ')">
                            <i class="ti-close"></i>
                        </button>
                    </div>
                </div>';
            } else {
                $this->delete_form_cart($item['id']);
            }
        }

        return response()->json(
            [
                'count' => count($cart ?? []),
                'produits' => $produits,
                'montant' => $montant . " DH",
                'html' => $html,
                'statut' => true,
            ]
        );
    }




    public function remove_to_card(Request $request)
    {
        $id = $request->input('id') ?? "";
        if ($id) {
            $this->delete_form_cart($id);
            return response()->json(
                [
                    'statut' => true,
                ]
            );
        }
    }




    public function add_panier(Request $request)
    {
        $id = $request->input('id') ?? "";
        $post = posts::where("id", $id)->where("statut", "vente")->first();
        if (!$post) {
            //json error
            return response()->json(
                [
                    'status' => true,
                    'message' => "Cet article n'est plus disponible a la vente",
                ]
            );
        }

        if ($post->id_user == Auth::user()->id) {
            return response()->json(
                [
                    'status' => true,
                    'message' => "Vous ne pouvez pas ajouter votre propre article dans votre panier",
                ]
            );
        }

        $cart = json_decode($_COOKIE['cart'] ?? '[]', true);
        $productExists = false;
        foreach ($cart ?? [] as $item) {
            if ($item['id'] == $post->id) {
                $productExists = true;
                break;
            }
        }

        if (!$productExists) {
            $cart[] = [
                'id' => $post->id,
            ];
            setcookie('cart', json_encode($cart), time() + (86400 * 30), '/');
            return response()->json(
                [
                    'status' => false,
                    'message' => "Article ajouté avec succès",
                ]
            );
        } else {
            return response()->json(
                [
                    'status' => true,
                    'message' => "Cet article est déjà dans votre panier",
                ]
            );
        }
    }




    public function like(Request $request)
    {
        $id_post = $request->input('id_post') ?? '';

        //verification de la connexion
        if (!Auth::check()) {
            return response()->json(
                [
                    'status' => true,
                    'message' => "Veuillez vous connecter",
                ]
            );
        }


        $post = posts::find($id_post);
        if (!$post) {
            return response()->json(
                [
                    'status' => false,
                    'message' => "Annonce introuvable",
                ]
            );
        }

        $liked = likes::where("id_post", $post->id)
            ->where('id_user', Auth::user()->id)
            ->exists();

        if ($liked === true) {
            likes::where("id_post", $post->id)
                ->where('id_user', Auth::user()->id)
                ->delete();
            return response()->json(
                [
                    'status' => false,
                    'liked' => false,
                    'message' => "Vous avez retiré votre like .",
                ]
            );
        } else {
            likes::firstOrCreate(
                [
                    'id_post' => $post->id,
                    'id_user' => Auth::user()->id
                ]
            );
            //make notification
            event(new UserEvent($post->id_user));
            $notification = new notifications();
            $notification->titre = Auth::user()->username . " a aimé votre publication.";
            $notification->id_user_destination = $post->id_user;
            $notification->type = "like";
            $notification->destination = "user";
            $notification->url = "/post/" . $post->id;
            $notification->message = "@" . Auth::user()->username . " Vient d'aimé votre publication";
            $notification->save();

            return response()->json(
                [
                    'status' => false,
                    'liked' => false,
                    'message' => "Like ajouté !",
                ]
            );
        }
    }





    public function delete_form_cart($id)
    {
        if (!is_numeric($id) || $id < 0) {
            return;
        }
        $cart = json_decode($_COOKIE['cart'] ?? '[]', true);
        foreach ($cart as $index => $item) {
            if ($item['id'] == $id) {
                unset($cart[$index]);
                break;
            }
        }
        $cart = array_values($cart);
        setcookie('cart', json_encode($cart), time() + (86400 * 30), '/');
    }





    public function conditions()
    {
        return view('User.faq.conditions');
    }

    public function inscription()
    {
        //check if user loged
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('User.Auth.inscription');
    }

    public function connexion()
    {
        //check if user loged
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('User.Auth.connexion');
    }

    public function forget()
    {
        return view('User.Auth.forget');
    }




    public function index_mes_achats(Request $request)
    {
        $date = $request->input('date' ?? null);
        $Query = posts::where("id_user_buy", Auth::id())->select("titre", "photos", "prix", "sell_at", "id");
        if (!empty($date)) {
            $Query->whereDate('sell_at', $date);
        }
        $achats = $Query->paginate(30);
        $total = posts::where("id_user_buy", Auth::id())->count();
        return view("User.mes-achats")
            ->with("achats", $achats)
            ->with('date', $date)
            ->with("total", $total);
    }


    public function checkout()
    {
        return view("User.checkout");
    }


    public function about()
    {
        return view("User.about");
    }


    public function how_buy()
    {
        return view("User.faq.howtobuy");
    }

    public function how_sell()
    {
        return view('User.faq.faq');
    }

    public function contact()
    {
        $configuration = configurations::first();
        return view('User.contact', compact("configuration"));
    }



    public function shopiners()
    {
        return view('User.shopiners');
    }


    public function shop(Request $request)
    {
        /* $categorie = $request->get('categorie') ?? $request->input('categorie') ?? '';
        $sous_categorie = $request->get('sous_categorie') ?? $request->input('sous_categorie') ?? '';
        
        */
        $key = $request->input("key" ?? null);
        $liste_categories = categories::orderBy('order')->get(["titre", "id", "luxury", "small_icon"]);
        $regions = regions::all();

        $luxury_only = $request->get('luxury_only');
        if (is_null($luxury_only)) {
            $luxury_only == "false";
        } else {
            $luxury_only == "true";
        }


        return view('User.shop')
            ->with("key", $key)
            ->with("luxury_only", $luxury_only)
            ->with('liste_categories', $liste_categories)
            ->with('regions', $regions);;
    }




    public function get_user_categorie_post(Request $request)
    {
        $id_user = $request->input("id_user");
        $categories = [];
        $user = User::find($id_user);
        if (!$user) {
            return response()->json([
                "status" => false,
                "message" => "Utilisateur introuvable!"
            ]);
        }
        foreach ($user->categoriesWhereUserPosted as $item) {
            $categories[] = [
                "nom" => $item->nom,
            ];
        }
        return response()->json([
            "status" => true,
            "data" =>  $categories,
            "username" => $user->username,
            "total" => $user->categoriesWhereUserPosted->count()
        ]);
    }
}
