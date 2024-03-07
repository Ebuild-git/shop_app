<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function liste_utilisateurs(Request $request)
    {
        if (isset($request->type)) {
            $type = $request->type;
        } else if ($request->type != "all"  && $request->type != "shop") {
            $type = "all";
        } else {
            $type = "all";
        }
        return view("Admin.clients.index")->with("type", $type);
    }

    public function details_user(Request $request)
    {
        $id = $request->id;
        try {
            $user = User::findOrFail($id);
            return view("Admin.clients.profile")->with("user", $user);
        } catch (\Throwable $th) {
            //throw $th;
            // 404
            abort(404, "Page non trouvée");
        }
    }



}
