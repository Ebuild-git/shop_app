<?php

namespace App\Http\Controllers;

use App\Models\categories;
use App\Models\posts;
use App\Models\proprietes;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

class AdminController extends Controller
{

    public function show_admin_dashboard(Request $request)
    {
        // Récupérer l'année spécifiée dans la requête ou utiliser l'année actuelle
        $date = $request->input('das_date', date("Y"));

        $stats_inscription = [];
        $stats_publication = [];

        // Boucle sur les 12 mois
        for ($i = 1; $i <= 12; $i++) {
            $currentDate = Carbon::createFromDate($date, $i, 1);
            $stats_inscription[] = User::whereYear('created_at', $currentDate->year)
                ->whereMonth('created_at', $currentDate->month)
                ->count();
            $stats_publication[] = posts::whereYear('created_at', $currentDate->year)
                ->whereMonth('created_at', $currentDate->month)
                ->count();
        }

        $stats_inscription_publication = [
            'inscription' => $stats_inscription,
            'publication' => $stats_publication
        ];

        $commandes_en_cour = posts::where("statut", "livraison")->get(["titre", "id", "id_region", "sell_at", "photos"]);
        $genres=[
            "homme"=> User::where('gender','male')->count(),
            'femme'=>User::where('gender','female')->count()
        ];

        return view('Admin.dashboard', compact("commandes_en_cour", "date", "stats_inscription_publication","genres"));
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


    public function admin_settings(){
        return view('Admin.parametre.index');
    }

    public function admin_settings_security(){
        return view('Admin.parametre.security');
    }



    public function update_propriete($id){
        $propriete = proprietes::find($id);
        $proprietes = proprietes::all();
        if(!$propriete){
            abort(404);
        }
        return view("Admin.categories.update_propriete", compact('propriete',"proprietes"));
    }


    public function export_users(){
        return Excel::download(new UsersExport, 'users.xlsx');
    }




    public function nos_partenaires(){
        return view ("Admin.informations.partenaires");
    }
 


}
