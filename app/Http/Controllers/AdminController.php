<?php

namespace App\Http\Controllers;

use App\Models\posts;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function show_admin_dashboard(Request $request)
    {
        $date = $request->get('das_date') ?? date("Y");
        $stats_inscription = [];
        $stats_publication = [];
        // Boucle sur les 12 mois
        for ($i = 1; $i <= 12; $i++) {
            // Générer la date pour le mois en cours de l'année en cours
            $date = Carbon::now()->startOfMonth()->subMonths(12 - $i);

            // Obtenir le compte de tous les utilisateurs inscrits pour le mois en cours
            $stats_inscription[] = User::whereYear('created_at', '=', $date->year)
                ->whereMonth('created_at', '=', $date->month)
                ->count();

            // Obtenir le compte de toutes les publications pour le mois en cours
            $stats_publication[] = posts::whereYear('created_at', '=', $date->year)
                ->whereMonth('created_at', '=', $date->month)
                ->count();
        }

        // Inverser les tableaux pour afficher les statistiques dans l'ordre chronologique
        $stats_inscription = array_reverse($stats_inscription);
        $stats_publication = array_reverse($stats_publication);

        $commandes_en_cour = posts::where("statut", "livraison")->get(["titre", "id", "gouvernorat", "sell_at", "photos"]);
        return view('Admin.dashboard', compact("commandes_en_cour", "date", "stats_inscription_publication"));
    }
}
