<?php

namespace App\Http\Controllers;

use App\Models\categories;
use App\Models\posts;
use App\Models\proprietes;
use App\Models\User;
use App\Models\UserCart;
use App\Models\regions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Commande;
use App\Events\UserEvent;
use App\Models\notifications;

class AdminController extends Controller
{


    public function show_admin_dashboard(Request $request)
    {
        $year = (int) $request->input('das_date', date("Y"));

        $stats_inscription = [];
        $stats_publication = [];

        for ($month = 1; $month <= 12; $month++) {
            $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            $stats_inscription[] = User::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->where('role', '!=', 'admin')
                ->where('locked', false)
                ->count();

            $stats_publication[] = posts::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->whereNull('deleted_at')
            ->count();
        }

        $stats_inscription_publication = [
            'inscription' => $stats_inscription,
            'publication' => $stats_publication,
        ];

        $commandes = Commande::OrderBy('created_at', 'desc')
                            ->paginate(10);
        $genres = [
            "homme" => User::where('gender', 'male')->where('role', '!=', 'admin')->where('locked', false)->count(),
            "femme" => User::where('gender', 'female')->where('role', '!=', 'admin')->where('locked', false)->count(),
        ];

        return view('Admin.dashboard', compact("commandes", "year", "stats_inscription_publication", "genres"));
    }

    public function add_sous_categorie($id)
    {
        $categorie = categories::find($id);
        if ($categorie) {
            return view('Admin.categories.add_sous_categorie')->with('categorie', $categorie);
        } else {
            abort(404);
        }
    }


    public function admin_settings()
    {
        return view('Admin.parametre.index');
    }

    public function admin_settings_security()
    {
        return view('Admin.parametre.security');
    }



    public function update_propriete($id)
    {
        $propriete = proprietes::find($id);
        $proprietes = proprietes::all();
        if (!$propriete) {
            abort(404);
        }
        return view("Admin.categories.update_propriete", compact('propriete', "proprietes"));
    }


    public function export_users()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }



    public function index_login()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view("auth.login");
    }


    public function post_login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ], [
            'required' => 'Ce champ est obligatoire.',
            'email' => 'Veuillez entrer une adresse email valide.',
            'exists' => "Cette adresse email n'existe pas.",
        ]);
        $user = User::where('email',$request->email)
            ->where("role", "admin")
            ->first();
        if (!$user) {
            return redirect()->back()->with('error', 'Cet e-mail n\'existe pas autorisé!');
        }
        $remember = $request->has('remember');
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $remember)) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->back()->with('error', 'Echec de connexion');
        }
    }




    public function index_logout(){
        $user = Auth::user();
        $cart = json_decode($_COOKIE['cart'] ?? '[]', true);

        if ($user && $cart) {
            foreach ($cart as $item) {
                $postExists = posts::where('id', $item['id'])->exists();
                if ($postExists) {
                    UserCart::updateOrCreate(
                        ['user_id' => $user->id, 'post_id' => $item['id']]
                    );
                }

            }
        }

        Auth::logout();
        setcookie('cart', '', time() - 3600, '/', null, null, true);
        return redirect('/')->with('clearLocalStorage', true);
    }

    public function index_categories()
    {
        $categories = categories::all();
        $totalCategories = $categories->count();
        return view("Admin.categories.index" , compact("totalCategories"));
    }
    public function index_proprietes(){
        $proprietes = proprietes::all();
        $totalProprietes = $proprietes->count();
        return view("Admin.categories.index_proprietes" , compact("totalProprietes"));
    }

    public function orders(Request $request){

        $query = Commande::orderBy('created_at', 'desc');

        if ($request->filled('region_id')) {
            $regionId = $request->region_id;

            $query->where(function ($subQuery) use ($regionId) {
                $subQuery->whereHas('vendor', function ($q) use ($regionId) {
                    $q->where('region', $regionId);
                })->orWhereHas('buyer', function ($q) use ($regionId) {
                    $q->where('region', $regionId);
                });
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $commandes = $query->paginate(10);
        $regions = regions::all();
        return view("Admin.shipement.shipement", compact("commandes", "regions"));
    }

    public function approveCIN($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->cin_approved = true;
            $user->save();

            event(new UserEvent($user->id));
            $notification = new notifications();
            $notification->titre = __('cin_notification_title');
            $notification->id_user_destination = $user->id;
            $notification->type = "alerte";
            $notification->destination = "user";
            $notification->message = __('cin_notification_message');
            $notification->save();


            return response()->json([
                'success' => true,
                'message' => 'CIN approved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error approving CIN: ' . $e->getMessage()
            ], 500);
        }
    }
}
