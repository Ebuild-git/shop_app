<?php

namespace App\Http\Controllers;

use App\Models\posts;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function show_admin_dashboard(){
        $commandes_en_cour = posts::where("statut","livraison")->get();
        return view('Admin.dashboard');
    }
}
