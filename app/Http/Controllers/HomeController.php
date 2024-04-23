<?php

namespace App\Http\Controllers;

use App\Events\AdminEvent;
use App\Events\MyEvent;
use App\Mail\VerifyMail;
use App\Models\categories;
use App\Models\configurations;
use App\Models\notifications;
use App\Models\posts;
use App\Models\regions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index()
    {
        $categories = categories::all();
        $configuration = configurations::firstorCreate();
        $posts = posts::where('verified_at', '!=', null)->orderByDesc('created_at')->paginate(50);
        $last_post = posts::where('verified_at', '!=', null)
            ->select("id", "titre", "photos", "old_prix", "prix", "id_sous_categorie", "statut")
            ->orderByDesc('created_at')
            ->get();
        $luxurys = posts::join('sous_categories', 'posts.id_sous_categorie', '=', 'sous_categories.id')
            ->join('categories', 'sous_categories.id_categorie', '=', 'categories.id')
            ->where('categories.luxury', true)
            ->select("posts.id", 'posts.old_prix', "posts.titre", "posts.photos", "posts.prix", "posts.statut", "posts.id_sous_categorie", "categories.id As id_categorie")
            ->get();
        return view("User.index", compact("categories", "posts", "configuration", "last_post", "luxurys"));
    }

    public function index_post(Request $request)
    {
        $id = $request->id ?? "";
        return view('User.post', compact("id"));
    }

    public function index_mes_post()
    {
        return view('User.list_post');
    }

    public function details_post($id)
    {
        $post = posts::find($id);
        if (!$post) {
            abort(404);
        }
        $other_product = posts::where('id_sous_categorie', $post->id_sous_categorie)
            ->select("titre", "photos", "prix", "id", "statut", "id_sous_categorie")
            ->where("verified_at", '!=', null)
            ->inRandomOrder()
            ->take(16)
            ->get();
        return view('User.details')
            ->with("post", $post)
            ->with("other_products", $other_product);
    }

    public function user_profile($id)
    {
        $user = User::find($id);
        return view('User.profile')->with("user", $user);
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
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', 'string'],
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'matricule' => 'nullable|mimes:jpg,png,jpeg,pdf|max:2048',
            'nom' => ['required', 'string'],
            'prenom' => ['required', 'string'],
            'adress' => ['nullable', 'string'],
            'telephone' => ['required', 'string'],
            'username' => "string|unique:users,username",
            'genre' => 'required|in:female,male',
            'jour' => 'required|integer|between:1,31',
            'mois' => 'required|integer|between:1,12',
            'annee' => 'required|integer|between:1950,2024',
        ]);

        $date = \Carbon\Carbon::createFromDate($request->annee, $request->mois, $request->jour);

        // Calculer l'âge avec précision
        $age = $date->diffInYears(\Carbon\Carbon::now());



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
        foreach ($cart as $item) {
            $produit = posts::find($item['id']);
            if (!$produit) {
                $this->delete($item['id']);
            }
        }

        $cart2 = json_decode($_COOKIE['cart'] ?? '[]', true);
        return response()->json(
            [
                'count' => count($cart2),
                'cart' => $cart2,
            ]
        );
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



    public function user_notifications()
    {
        return view('User.notifications');
    }

    public function conditions()
    {
        return view('User.conditions');
    }

    public function inscription()
    {
        return view('User.Auth-user.inscription');
    }

    public function connexion()
    {
        return view('User.Auth-user.connexion');
    }

    public function forget()
    {
        return view('User.Auth-user.forget');
    }


    public function favoris()
    {
        return view("User.favoris");
    }

    public function index_mes_achats()
    {
        return view("User.mes-achats");
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
        return view("User.howtobuy");
    }

    public function how_sell()
    {
        return view('User.faq');
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
        $categorie = $request->get('categorie') ?? $request->input('categorie') ?? '';
        $sous_categorie = $request->get('sous_categorie') ?? $request->input('sous_categorie') ?? '';
        $key = $request->input("key", '');
        return view('User.shop', compact("categorie", "sous_categorie", "key"));
    }
}
