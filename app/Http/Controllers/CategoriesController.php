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


    public function update_categorie($id)
    {
        $categorie = categories::find($id);
        if (!$categorie) {
            abort(404);
        }
        return view("Admin.categories.update_categorie")->with('categorie', $categorie);
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
            'title_en' => 'nullable|string',
            'title_ar' => 'nullable|string',
            'id_categorie' => 'integer|exists:categories,id',
            'option.*' => 'nullable|integer|exists:proprietes,id',
            'required.*' => 'nullable|string',
        ]);



        $sous_categorie = sous_categories::find($request->id);
        if (!$sous_categorie) {
            abort(404);
        }

        $options = $request->input('option');
        $required = $request->input('required');
        $indexesArray = [];
        $test = [];

        if ($options) {
            $indexes = array_keys($options, true);
            foreach ($indexes as $index) {
                $indexesArray[] = $index;
                $status = $required[$index] ?? 'Non';
                $test[] = [
                    'id' => $index,
                    'required' => $status,
                ];
            }
        }
        $jsonIndexes = $indexesArray;



        $sous_categorie->id_categorie = $request->id_categorie;
        $sous_categorie->proprietes = $jsonIndexes ?? [];
        $sous_categorie->titre = $request->titre;
        $sous_categorie->title_en = $request->title_en;
        $sous_categorie->title_ar = $request->title_ar;
        $sous_categorie->required = json_encode($test) ?? [];
        $sous_categorie->save();

        return redirect()->back()->with('success', 'La modification a été enregistré !');
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




    public function changerOrdresous()
    {
        $ids = request()->get('ids');
        $idsArray = explode(',', $ids);
        foreach ($idsArray as $index => $id) {
            $enregistrement = sous_categories::findOrFail($id);
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
            $categorie->proprietes = $tabIds;
            $categorie->save();
        }
        return response()->json(['success' => true]);
    }

    public function changer_ordre_attribus(){
        $ids = request()->get('ids');
        $id_propriete = request()->get('id_propriete');
        $propriete = proprietes::find($id_propriete);
        if($propriete){

        $tabIds = explode(",", $ids);
        $sortedIds = [];
        foreach ($tabIds as $id) {
                $sortedIds[] = str($id);
        }
        $propriete->options= $sortedIds;
        $propriete->save();;
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








}
