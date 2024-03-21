<?php

namespace App\Http\Controllers;

use App\Models\categories;
use App\Models\proprietes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{

public function add_categorie(){
    return view( 'Admin.categories.ajouter_categorie');
}

    public function list_categorie()
    {
        $categories = categories::all();
        return response()->json(
            [
                'success' => true,
                'data' => $categories
            ]
        );
    }

    public function changerOrdre()
    {
        $ids = request()->get('ids');
        $idsArray = explode(',', $ids);
        foreach ($idsArray as $index => $id) {
            $enregistrement = categories::findOrFail($id);
            $enregistrement->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }


    public function changerOrdrepropriete(){
        $ids = request()->get('ids');
        $idsArray = explode(',', $ids);
        foreach ($idsArray as $index => $id) {
            $enregistrement = proprietes::findOrFail($id);
            $enregistrement->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    public function changer_ordre_propriete_in_categorie(){
        $ids = request()->get('ids');
        $id_cat = request()->get('id_cat');
        $categorie = categories::find($id_cat);
        if($categorie){
            //convert $ids to arry
            $tabIds = array_map('intval',explode(",",$ids));
            $categorie->proprietes =  $tabIds;
            $categorie->save();
        }
        return response()->json(['success' => true]);
    }




    public function details_categorie($id)
    {
        try {
            $categories = categories::findOrFail($id);
            return response()->json(
                [
                    'success' => true,
                    'message' => 'La catégorie a été trouver',
                    'data' => $categories
                ]
            );
        } catch (\Exception $exception) {
            return response()->json(
                [
                    'success' => false,
                    'message' => "Impossible de trouver la categorie"
                ]
            );
        }
    }




    public function delete_categorie($id)
    {
        try {
            $categorie = categories::findOrFail($id);
            $this->delete_trait($categorie->icon);
            $categorie->delete();
            return response()->json(
                [
                    'success' => true,
                    'message' => 'La catégorie a été supprimé',
                ]
            );
        } catch (\Exception $exception) {
            return response()->json(
                [
                    'success' => false,
                    'message' => "Impossible de trouver la categorie"
                ]
            );
        }
    }



    public function create_categorie(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'required|string',
            'description' => 'required|string',
            'icon' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Veuillez verifier les champs !',
                "errors" => $validator->errors()
            ]);
        }
        //upload image
        $icon = $request->file('icon');
        $image = $this->upload_trait($icon);

        $categorie = new categories();
        $categorie->titre = $request->input("titre");
        $categorie->description = $request->input('description');
        $categorie->icon = $image;
        $categorie->save();

        return response()->json(
            [
                'success' => true,
                'message' => 'Categorie ajouté'
            ]
        );
    }




    public function update_categorie(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'required|string',
            'id_categorie' => 'required|integer|exists:categories,id',
            'description' => 'required|string',
            'icon' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Veuillez verifier les champs !',
                "errors" => $validator->errors()
            ]);
        }
        $categorie = categories::find($request->input('id_categorie'));
        if ($request->hasFile('icon')) {
            $this->delete_trait($categorie->icon);
            $icon = $request->file('icon');
            $image = $this->upload_trait($icon);
        }
        $categorie->titre = $request->input("titre");
        $categorie->description = $request->input('description');
        $categorie->icon = $image;
        $categorie->save();

        return response()->json(
            [
                'success' => true,
                'message' => 'Categorie modifié'
            ]
        );
    }
}
