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
use App\Models\ratings;
use App\Models\regions;
use App\Models\signalements;
use App\Models\sous_categories;
use App\Models\User;
use App\Models\UserCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;

class HomeController extends Controller
{

    public function index()
    {
        $categories = categories::all();
        $configuration = configurations::firstOrCreate();
        $usersWithVoyageMode = User::where('voyage_mode', true)->pluck('id');
        // Fetch non-luxury posts
        $last_post = posts::join('sous_categories', 'posts.id_sous_categorie', '=', 'sous_categories.id')
            ->join('categories', 'sous_categories.id_categorie', '=', 'categories.id')
            ->where('categories.luxury', false)
            ->whereNull('posts.sell_at')
            ->whereNotIn('id_user', $usersWithVoyageMode)
            ->select("posts.id", "posts.photos", "posts.prix", "posts.old_prix")
            ->orderBy("posts.created_at", "Desc")
            ->orderBy("posts.updated_price_at", "Desc")
            ->take(12)
            ->get();
        // Fetch luxury posts
        $luxurys = posts::join('sous_categories', 'posts.id_sous_categorie', '=', 'sous_categories.id')
            ->join('categories', 'sous_categories.id_categorie', '=', 'categories.id')
            ->where('categories.luxury', true)
            ->whereNull('posts.sell_at')
            ->orderBy("posts.created_at", "Desc")
            ->select("posts.id", "posts.photos", "posts.prix", "posts.old_prix")
            ->take(9)->get();



        // Calculate the discount percentage for each post
        foreach ($last_post as $post) {
            $post->discountPercentage = null;
            if ($post->old_prix && $post->old_prix > $post->prix) {
                $post->discountPercentage = round((($post->old_prix - $post->prix) / $post->old_prix) * 100);
            }
        }

        foreach ($luxurys as $lux) {
            $lux->discountPercentage = null;
            if ($lux->old_prix && $lux->old_prix > $lux->prix) {
                $lux->discountPercentage = round((($lux->old_prix - $lux->prix) / $lux->old_prix) * 100);
            }
        }
        // Controller method
        $categories_carousel = categories::orderBy('titre')
            ->orderByDesc('luxury')
            ->get();

        return view("User.index", compact("categories", "configuration", "last_post", "luxurys", "categories_carousel"));
    }


    public function index_post(Request $request)
    {
        $id = $request->id ?? "";
        $step = $request->input('step', 1);
        return view('User.post', compact("id", "step"));
    }

    public function showRibForm(Request $request)
    {
        $step = $request->input('step', 1);
        return view('rib-form', compact('step'));
    }

    public function submitRib(Request $request)
    {
        $request->validate([
            'ribNumber' => 'required|string',
            'bankName' => 'required|string',
            'titulaireName' => 'required|string',
        ]);

        $user = Auth::user();
        $isNew = empty($user->rib_number);

        // Encrypt and save the RIB number
        $encryptedRib = Crypt::encryptString($request->input('ribNumber'));
        $user->rib_number = $encryptedRib;

        // Save the bank name and titulaire name
        $user->bank_name = $request->input('bankName');
        $user->titulaire_name = $request->input('titulaireName');

        $user->save();

        $message = $isNew ? 'RIB ajouté avec succès.' : 'RIB modifié avec succès.';

        return response()->json(['message' => $message]);
    }

    public function index_mes_post(Request $request)
    {
        $month = $request->input('month') ?? null;
        $year = $request->input('year') ?? null;
        $type = $request->get('type') ?? "annonce";
        $statut = $request->input('statut') ?? null;
        $key = $request->input("key") ?? null;
        $Query = posts::where("id_user", Auth::user()->id)->Orderby("id", "Desc");
        if ($key) {
            $Query->where("titre", "LIKE", "%{$key}%")
                ->orWhere("description", "LIKE", "%{$key}%");
        }

        if ($type != "annonce") {
            $type = "vente";
        }
        if ($type == "vente") {
            $Query = $Query->where('statut', "vendu");

            if ($month && $year) {
                $Query->whereYear('sell_at', $year)
                      ->whereMonth('sell_at', $month);
            }
        } else {
            $Query = $Query->where('statut', "vente");

            if ($month && $year) {
                $Query->whereYear('created_at', $year)
                      ->whereMonth('created_at', $month);
            }
        }

        if (!empty($statut)) {
            switch ($statut) {
                case 'validation':
                    $Query->where('statut', 'validation');
                    break;
                case 'vente':
                    $Query->where(function($query) {
                        $query->where('statut', 'vente')
                              ->orWhere(function($subQuery) {
                                  $subQuery->where('verified_at', '!=', null)
                                           ->where('sell_at', null)
                                           ->whereHas('user_info', function($userQuery) {
                                            $userQuery->where('voyage_mode', 0); // voyage_mode should be false (0)
                                        });
                              });
                    });
                    break;
                case 'vendu':
                    $Query->where('statut', 'vendu')
                          ->orWhere('sell_at', '!=', null);
                    break;
                case 'livraison':
                    $Query->where('statut', 'livraison');
                    break;
                case 'livré':
                    $Query->where('statut', 'livré');
                    break;
                case 'refusé':
                    $Query->where('statut', 'refusé');
                    break;
                case 'préparation':
                    $Query->where('statut', 'préparation');
                    break;
                case 'en cours de livraison':
                    $Query->where('statut', 'en cours de livraison');
                    break;
                case 'ramassée':
                    $Query->where('statut', 'ramassée');
                    break;
                case 'retourné':
                    $Query->where('statut', 'retourné');
                    break;
                case 'en voyage':
                    $Query->where(function($query) {
                        $query->where('statut', 'en voyage')
                              ->orWhere(function($subQuery) {
                                  $subQuery->whereHas('user_info', function($userQuery) {
                                      $userQuery->where('voyage_mode', 1);
                                  })
                                  ->where('verified_at', '!=', null)
                                  ->where('sell_at', null);
                              });
                    });
                    break;
                default:
                    // Handle unknown status or fallback
                    $Query->where('statut', 'like', "%{$statut}%");
                    break;
            }
        }

        $posts = $Query->paginate("20");
        return view('User.list_post', [
            'posts' => $posts,
            'year' => $year,
            'month' => $month,
            'statut' => $statut,
            'type' => $type,
            'key' => $key,
            'currentPage' => $posts->currentPage(),
            'lastPage' => $posts->lastPage(),
            'nextPageUrl' => $posts->nextPageUrl(),
            'previousPageUrl' => $posts->previousPageUrl(),
            'totalItems' => $posts->total(),
        ]);
    }

    public function details_post($id)
    {
        $post = posts::find($id);
        if (!$post) {
            abort(404);
        }
        $user = $post->user_info;
        $ipAddress = request()->ip();

        $viewedIps = json_decode($post->ip_address, true) ?? [];

        if (!in_array($ipAddress, $viewedIps)) {
            $post->increment('views');
            $viewedIps[] = $ipAddress;
            $post->ip_address = json_encode($viewedIps);
            $post->save();
        }

        //si un user est connecter verifier si ce post est dans ses favoris
        if (Auth::check()) {
            $isFavorited =  favoris::where('id_post', $post->id)->where('id_user', Auth::user()->id)->exists();
            $isLiked = likes::where('id_post', $post->id)->where('id_user', Auth::user()->id)->exists();
        } else {
            $isFavorited = false;
            $isLiked = false;
        }

        if (Auth::check()) {
            $is_alredy_signaler = signalements::where('id_user_make', Auth::user()->id)
                ->where('id_post', $post->id)->count();
        }


        //verifier si j'ai cet article dans mon panier
        $cart = json_decode($_COOKIE['cart'] ?? '[]', true);
        $productExists = false;
        foreach ($cart ?? [] as $item) {
            if ($item['id'] == $post->id) {
                $productExists = true;
                break;
            }
        }
        if ($productExists) {
            $produit_in_cart = true;
        } else {
            $produit_in_cart = false;
        }

        $ma_note = null;

        if (Auth::check()) {
            $ma_note = ratings::where('id_user_buy', Auth::user()->id)
                ->where('id_user_sell', $user->id)
                ->first();
            if ($ma_note) {
                $ma_note = $ma_note->etoiles;
            }
        }

        $usersWithVoyageMode = User::where('voyage_mode', true)->pluck('id');

        $other_product = posts::where('id_sous_categorie', $post->id_sous_categorie)
            ->select("photos", "id")
            ->where("verified_at", '!=', null)
            ->whereNotIn('id_user', $usersWithVoyageMode)
            ->inRandomOrder()
            ->take(16)
            ->get();
        $user_product = posts::where('id_user', $post->id_user)
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
            ->with("other_products", $other_product)
            ->with("user_products", $user_product)
            ->with("ma_note", $ma_note)
            ->with("is_alredy_signaler", $is_alredy_signaler ?? false)
            ->with("produit_in_cart", $produit_in_cart);
    }



    public function user_profile($id)
    {

        $user = User::find($id);

        // Check if the user is in voyage mode
        if ($user->voyage_mode) {
            $posts = collect();
        } else {
            $posts = posts::where("id_user", $user->id)->get()->map(function($post) {
                $post->discountPercentage = null;
                if ($post->old_prix && $post->old_prix > $post->prix) {
                    $post->discountPercentage = round((($post->old_prix - $post->prix) / $post->old_prix) * 100);
                }
                return $post;
            });
        }

        $notes = ratings::where('id_user_sell', $user->id)->avg('etoiles');
        $ma_note = ratings::where('id_user_buy', Auth::user()->id)
            ->where("id_user_sell", $user->id)
            ->first();
        if ($ma_note) {
            $ma_note = $ma_note->etoiles;
        }
        $count = number_format($user->averageRating->average_rating ?? 1);
        $avis = $user->getReviewsAttribute->count();

        return view('User.profile')
            ->with("user", $user)
            ->with('posts', $posts)
            ->with('notes', $notes)
            ->with('ma_note', $ma_note)
            ->with('count', $count)
            ->with('avis', $avis);
    }

    public function informations()
    {
        return view('User.infromations');
    }

    public function historiques($type)
    {
        $AllowedType = ["achats", "ventes", "annonces"];
        if (!in_array($type, $AllowedType)) {
            $type = "acahats";
        }


        $count = posts::where("id_user", Auth::user()->id)->count();
        $showRemainingTimeColumn = $type == "ventes";

        if ($type == "achats") {
            $achats = posts::where("id_user_buy", Auth::id())
                ->paginate(20);
            return view('User.historiques', compact("type", "count", "achats"));
        }
        if ($type == "ventes") {
            $ventes = posts::where("id_user", Auth::user()->id)
                ->Orderby("id", "Desc")
                ->where('statut', "vendu")
                ->paginate(20);

        // Pagination variables
        $currentPage = $ventes->currentPage();
        $lastPage = $ventes->lastPage();
        $nextPageUrl = $ventes->nextPageUrl();
        $previousPageUrl = $ventes->previousPageUrl();
        $totalItems = $ventes->total();

        return view('User.historiques', compact(
            "type", "count", "ventes", "showRemainingTimeColumn",
            "currentPage", "lastPage", "nextPageUrl", "previousPageUrl", "totalItems"
        ));
        }
        if ($type == "annonces") {
            $annonces = posts::where("id_user", Auth::user()->id)
                ->Orderby("id", "Desc")
                ->where('statut', "vente")
                ->paginate(20);


             // Pagination variables
            $currentPage = $annonces->currentPage();
            $lastPage = $annonces->lastPage();
            $nextPageUrl = $annonces->nextPageUrl();
            $previousPageUrl = $annonces->previousPageUrl();
            $totalItems = $annonces->total();
            return view('User.historiques', compact(
                "type", "count", "annonces", "showRemainingTimeColumn",
                "currentPage", "lastPage", "nextPageUrl", "previousPageUrl", "totalItems"
            ));
        }
    }








    public function inscription_post(Request $request)
    {
        $year = date('Y');
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ],
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'matricule' => 'nullable|mimes:jpg,png,jpeg,pdf|max:2048',
            'nom' => ['required', 'string'],
            'prenom' => ['required', 'string'],
            'adresse' => ['required', 'string'],
            'telephone' => ['required', 'string', 'Max:15'],
            'username' => "string|unique:users,username",
            'genre' => 'required|in:female,male',
            'jour' => 'required|integer|between:1,31',
            'mois' => 'required|integer|between:1,12',
            'annee' => 'required|integer|between:1950,2024',
            // Validation rules for new fields
            'ruee' => ['required', 'string'],
            'nom_batiment' => ['required', 'string'],
            'etage' => ['nullable', 'string'],
            'num_appartement' => ['nullable', 'string'],
        ], [
            'required' => "Veuillez renseigner ce lien.",
            'adresse.required' => "Veuillez renseigner la ville.",
            'ruee.required' => "Veuillez renseigner la rue.",
            'username.unique' => "Ce pseudo est déja utilisé.",
            'email.unique' => "Cette adresse email est déja utilisé.",
            "string" => "Veuillez entrer une valeur de type texte.",
            "password.min" => "Votre mot de passe doit contenir minimun 8 caractères.",
            "password.confirmed" => "Les mots de passe ne correspondent pas. Veuillez les vérifier et réessayer.",
            'password.regex' => 'Votre mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial(-!@# etc.).',
            "interger" => "Veuillez entrer une valeur de type entier.",
            "in.genre" => "Veuillez choisir votre sexe.",
            "mimes" => "Veuillez choisir un format de fichier valide.",
            "image" => "Veuillez choisir une image valide.",
            "max" => "Veuillez choisir un fichier de taille inférieur à 2Mo.",
            "between" => "Veuillez choisir une date valide.",

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
        $user->address = $request->adresse;
        $user->username = $request->username;
        $user->ip_address = request()->ip();
        $user->remember_token = $token;
        // Assign new fields
        $user->rue = $request->ruee;
        $user->nom_batiment = $request->nom_batiment;
        $user->etage = $request->etage;
        $user->num_appartement = $request->num_appartement;

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
        try{
            Mail::to($user->email)->send(new VerifyMail($user, $token));
        }catch(\Exception $e){
            return redirect("/connexion")->with("error", "Une erreur s'est produite lors de l'envoi du mail de validation. mais les compte a été créer !");
        }

        return redirect("/connexion")->with("success", "Votre compte a été créé avec succès!<br>Pour finaliser votre inscription, cliquez sur le lien de validation que nous vous avons envoyé par e-mail. Merci et bienvenue parmi nous!");
        //reset form
    }


    public function count_panier()
    {
        $user = Auth::user();
        $cartItems = UserCart::where('user_id', $user->id)->get();
        $produits = [];
        $montant = 0;
        $html = '';

        foreach ($cartItems as $item) {
            $produit = posts::where('id', $item->post_id)->where('id_user', '!=', $user->id)->where('statut', 'vente')->first();
            if ($produit) {
                $CartItem = view('components.cart-item', ['produit' => $produit])->render();
                $montant += $produit->getPrix();
                $html .= $CartItem;
            } else {
                $this->delete_form_cart($item->post_id);
            }
        }

        return response()->json(
            [
                'count' => $cartItems->count(),
                'produits' => $produits,
                'montant' => number_format($montant, 2, '.', '') . " DH",
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

            return response()->json([
                'status' => true,
                'message' => "Article retiré de votre panier",
                'exist' => false,
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid product ID',
        ]);
    }


    public function add_panier(Request $request)
    {
        $id = $request->input('id') ?? "";
        $post = posts::where("id", $id)->where("statut", "vente")->first();

        if (!$post) {
            return response()->json([
                'status' => true,
                'message' => "Cet article n'est plus disponible à la vente",
                'exist' => false,
            ]);
        }

        if ($post->id_user == Auth::user()->id) {
            return response()->json([
                'status' => true,
                'message' => "Vous ne pouvez pas ajouter votre propre article dans votre panier",
                'exist' => false,
            ]);
        }

        $user = Auth::user();
        $productExists = UserCart::where('user_id', $user->id)
                                ->where('post_id', $post->id)
                                ->exists();

        if (!$productExists) {
            // Add the product to the `user_carts` table
            UserCart::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);

            return response()->json([
                'status' => false,
                'message' => "Article ajouté avec succès",
                'exist' => true,
            ]);
        } else {
            // Remove the product from the `user_carts` table
            UserCart::where('user_id', $user->id)
                    ->where('post_id', $post->id)
                    ->delete();

            return response()->json([
                'status' => true,
                'message' => "Article retiré de votre panier",
                'exist' => false,
            ]);
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
            $notification->message = "@" . Auth::user()->username . " vient d'aimer votre publication";
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
        $user = Auth::user();

        UserCart::where('user_id', $user->id)
            ->where('post_id', $id)
            ->delete();
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

        $month = $request->input('month') ?? null;
        $year = $request->input('year') ?? null;

        $query = posts::where("id_user_buy", Auth::id())
            ->select("titre", "photos", "id_sous_categorie", 'id_user', 'statut', "prix", "sell_at", "id");

        if ($month && $year) {
            $query->whereYear('sell_at', $year)
                ->whereMonth('sell_at', $month);
        }

        $achats = $query->paginate(20);
        $total = posts::where("id_user_buy", Auth::id())->count();

        return view("User.mes-achats")
            ->with("achats", $achats)
            ->with("month", $month)
            ->with("year", $year)
            ->with("total", $total);
    }


    public function checkout(Request $request)
    {
        $step = $request->get('step') ?? 1;

        if ($step < 1 || $step > 4) {
            return redirect()->route('checkout');
        }

        return view("User.checkout")
            ->with('step', $step);
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
        $id_selected_categorie = $request->get("id_categorie") ?? null;
        $id_selected_sous_categorie = $request->get("selected_sous_categorie") ?? null;
        if ($id_selected_categorie) {
            $selected_categorie = categories::where('id', $id_selected_categorie)
                ->select("titre", "id", "luxury", "small_icon", "icon")
                ->first();
        }
        if ($id_selected_sous_categorie) {
            $selected_sous_categorie = sous_categories::find($id_selected_sous_categorie);
        }
        $liste_categories = categories::orderBy('order')->get(["titre", "id", "luxury", "small_icon", "icon"]);
        $key = $request->input("key") ?? null;

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
            ->with("selected_categorie", $selected_categorie ?? null)
            ->with('selected_sous_categorie', $selected_sous_categorie ?? null)
            ->with('regions', $regions);
    }




    public function get_user_categorie_post(Request $request)
    {
        $id_user = $request->input("id_user");
        $user = User::find($id_user);

        if (!$user) {
            return response()->json([
                "status" => false,
                "message" => "Utilisateur introuvable!"
            ]);
        }

        $categories = [];
        $posts = posts::where('id_user', $user->id)
            ->whereIn('statut', ['livré', 'vendu', 'livraison'])
            ->get();

        foreach ($posts as $post) {
            $sous_categorie = sous_categories::find($post->id_sous_categorie);
            if ($sous_categorie) {
                $categorie = categories::find($sous_categorie->id_categorie);
                if ($categorie) {
                    if (isset($categories[$categorie->titre])) {
                        $categories[$categorie->titre]++;
                    } else {
                        $categories[$categorie->titre] = 1;
                    }
                }
            }
        }

        // Convertir le tableau associatif en une liste pour la vue
        $categories_list = [];
        foreach ($categories as $nom => $count) {
            $categories_list[] = [
                "nom" => $nom,
                "count" => $count
            ];
        }

        $ListeHtml = view('components.Liste-categories-vendus', ['categories' => $categories_list])->render();
        return response()->json([
            "status" => true,
            "html" =>  $ListeHtml,
            "username" => $user->username,
            "total" => count($categories)
        ]);
    }
}
