<?php

namespace App\Http\Controllers;

use App\Events\MyEvent;
use App\Models\categories;
use App\Models\configurations;
use App\Models\posts;
use App\Models\regions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $categories = categories::all();
        $configuration = configurations::firstorCreate();
        $posts = posts::where('verified_at', '!=', null)->orderByDesc('created_at')->paginate(50);
        $last_post = posts::where('verified_at', '!=', null)
            ->select("id", "titre", "photos", "prix", "id_sous_categorie", "statut")
            ->orderByDesc('created_at')
            ->get();
        $luxurys = posts::join('sous_categories', 'posts.id_sous_categorie', '=', 'sous_categories.id')
            ->join('categories', 'sous_categories.id_categorie', '=', 'categories.id')
            ->where('categories.luxury', true)
            ->select("posts.id", "posts.titre", "posts.photos", "posts.statut", "posts.id_sous_categorie", "categories.id As id_categorie")
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
            ->select("titre", "photos", "id", "statut")
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

        //get all post where collun sell_at is not null group by id_user Asc

        $shopiners = User::select('users.id', 'users.name','users.avatar','users.username', DB::raw('AVG(etoiles) as average_rating'))
            ->join('ratings', 'users.id', '=', 'ratings.id_user_rated')
            ->groupBy('users.id', 'users.name','users.avatar','users.username')
            ->where('users.role', '!=', 'admin')
            ->orderByDesc('average_rating')
            ->limit(10)
            ->get();



        return view('User.shopiners', compact("shopiners"));
    }


    public function shop(Request $request)
    {
        $categorie = $request->get('categorie') ?? $request->input('categorie') ?? '';
        $key = $request->input("key", '');
        return view('User.shop', compact("categorie", "key"));
    }
}
