<?php

namespace App\Http\Controllers;

use App\Models\posts;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function show_admin_dashboard(Request $request){
        $date = $request->get('das_date') ?? date("Y");
        $stats_inscription =[];
        $stats_publication =[];
        for ($i=0; $i < 12; $i++) {
                // Obtenir le compte de tous les utilisateurs inscrits dans l'année $date
                $stats_inscription[] = User::whereYear('created_at', '=', $date)->count();
                // Obtenir le compte de toutes les publications dans l'année $date
                $stats_publication[] = posts::whereYear('created_at', '=', $date)->count();
        }
        $stats_inscription_publication = [
            'inscription' => $stats_inscription,
            'publication'=> $stats_publication
        ];
        $commandes_en_cour = posts::where("statut","livraison")->get(["titre","id","gouvernorat","sell_at","photos"]);
        return view('Admin.dashboard', compact("commandes_en_cour","date","stats_inscription_publication"));
    }
}
