<?php

namespace App\Http\Controllers;

use App\Models\configurations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PartenairesController extends Controller
{
    public function index()
    {
        $configuration = configurations::first();
        $logos = json_decode($configuration->partenaires) ?? [];
        return view("Admin.informations.partenaires")
            ->with("logos", $logos);
    }


    public function delete(Request $request){
        $url = $request->input('url');
            $configuration = configurations::first();
            $logos = json_decode($configuration->partenaires, true) ?? []; 
            $index = array_search($url, $logos);
            if($index !== false) {
                unset($logos[$index]);
                Storage::disk('public')->delete($url);
                $configuration->partenaires = json_encode($logos);
                $configuration->save();
            }
            return redirect()->back();
    }
    

    public function create(Request $request){
        //validation de l'image logo
        $validator = Validator::make($request->all(), [
            'logo' => ['required', 'image','Max:3000'],
        ]);
        $configuration = configurations::first();
        $logo = $request->file('logo')->store('uploads/partenaires', 'public');
        $logos = json_decode($configuration->partenaires) ?? [];
        array_push($logos, $logo);
        $configuration->partenaires = json_encode($logos);
        $configuration->save();

        return redirect()->back()->with("success","Enregistrement effectué !");
    }


}
