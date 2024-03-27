<?php

namespace App\Http\Controllers;

use App\Models\categories;
use App\Models\proprietes;
use App\Models\sous_categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{

    public function add_categorie()
    {
        return view('Admin.categories.ajouter_categorie');
    }

    public function add_regions()
    {
        return view('Admin.categories.ajouter-regions');
    }

    public function grille_prix()
    {
        return view('Admin.categories.grille_prix');
    }


    public function update_sous_categorie($id)
    {
        $sous_categorie = sous_categories::find($id);
        $mes_proprietes = [];
        if (!$sous_categorie) {
            abort(404);
        }
        $proprietes = proprietes::all();
        foreach ($proprietes as $item) {
            $mes_proprietes[] = [
                "id" => $item->id,
                "isChecked" => in_array($item->id, $sous_categorie->proprietes) ? true : false,
                "nom" => $item->nom
            ];
        }
        $categories = categories::all(["id", "titre"]);
        return view('Admin.categories.update_sous_categorie', compact('sous_categorie', 'mes_proprietes', 'categories'));
    }

    public function post_update_sous_categorie(Request $request)
    {

        //validation
        $this->validate($request, [
            'titre' => 'required|string',
            'id_categorie' => 'integer|exists:categories,id',
            'option.*' => 'nullable|integer|exists:proprietes,id'
        ]);



        $sous_categorie = sous_categories::find($request->id);
        if (!$sous_categorie) {
            abort(404);
        }

        $options = $request->input('option');

        $indexes = array_keys($options, true);
        $indexesArray = [];
        foreach ($indexes as $index) {
            $indexesArray[] = $index;
        }
        $jsonIndexes = $indexesArray;



        $sous_categorie->id_categorie  = $request->id_categorie;
        $sous_categorie->proprietes = $jsonIndexes ??  [];
        $sous_categorie->titre = $request->titre;
        $sous_categorie->save();

        return redirect()->back()->with('success', 'La modification a été enregidtré !');
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


    public function changerOrdrepropriete()
    {
        $ids = request()->get('ids');
        $idsArray = explode(',', $ids);
        foreach ($idsArray as $index => $id) {
            $enregistrement = proprietes::findOrFail($id);
            $enregistrement->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    public function changer_ordre_propriete_in_sous_categorie()
    {
        $ids = request()->get('ids');
        $id_sous_cat = request()->get('id_sous_cat');
        $categorie = sous_categories::find($id_sous_cat);
        if ($categorie) {
            //convert $ids to arry
            $tabIds = array_map('intval', explode(",", $ids));
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




    public function update_categorie($id)
    {
        $categorie = categories::find($id);
        if (!$categorie) {
            abort(404);
        }
        return view("Admin.categories.update_categorie")->with('categorie', $categorie);
    }
}
