<?php

namespace App\Http\Controllers;

use App\Models\posts;
use App\Models\proprietes;
use App\Models\sous_categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    //controller dedier a la recherche

    public function index(Request $request)
    {
        $gouvernorat = $request->input('gouvernorat' ?? null);
        $region = $request->input('region' ?? null);
        $proprietes = json_decode($request->input('proprietes'), true);
        $sous_categorie = $request->input('sous_categorie' ?? null);
        $ordre_prix = $request->input('ordre_prix' ?? null);
        $ordre_creation = $request->input('ordre_creation' ?? null);
        $categorie = $request->input('categorie' ?? null);
        $key = $request->input('key' ?? null);
        $etat = $request->input('etat' ?? null);
        $luxury_only = $request->input('check_luxury' ?? null);
        $html = "";


        $total = posts::whereNotNull('verified_at')->whereNull('sell_at')->count();

        $query = posts::whereNotNull('verified_at')
            ->whereNull('sell_at')
            ->where('statut', 'vente');


        if ($luxury_only == "true") {
            //sachant que le post est lier a une sous categorie qui est lier a une categoerie, je veux tout les post donc la categorie est luxuryt true
            $query->whereHas('sous_categorie_info.categorie', function ($q) {
                $q->where('luxury', true);
            });
        }

        if ($ordre_creation) {
            $query->orderBy('created_at', ($ordre_creation == "Desc") ? 'DESC' : 'ASC');
        }

        if ($region) {
            $query->where('id_region', $region);
        }

        if ($ordre_prix) {
            if ($ordre_prix == "Desc") {
                $query->orderBy('prix', 'Desc');
            } elseif ($ordre_prix == "Asc") {
                $query->orderBy('prix', 'Asc');
            } elseif ($ordre_prix == "Soldé") {
                $query->whereHas('changements_prix', function ($q) {
                    $q->whereNotNull('id'); // Vérifie qu'il y a au moins un changement de prix
                });
            }
        }


        if ($gouvernorat) {
            $query->where('gouvernorat', $gouvernorat);
        }





        if ($proprietes) {
            if (!is_array($proprietes) || !isset($proprietes['type']) || !isset($proprietes['valeur'])) {
                return response()->json(['error' => 'Invalid JSON data'], 400);
            }

            // Conversion en minuscules pour la recherche insensible à la casse
            $type = strtolower($proprietes['type']);
            $valeur = strtolower($proprietes['valeur']);


            // Ajouter la condition de recherche
            $query->where(function ($query) use ($type, $valeur) {
                $query->whereRaw('LOWER(proprietes) LIKE ?', ['%\"' . $valeur . '\"%']);
            });
        }




        if ($key) {
            $q = strtolower($key); // Convertir la recherche en minuscules

            $query->where(function ($query) use ($q) {
                $query->whereRaw('LOWER(titre) LIKE ?', ['%' . $q . '%']) // Recherche insensible à la casse sur la colonne 'titre'
                    ->orWhereRaw('LOWER(proprietes) LIKE ?', ['%' . $q . '%']) // Recherche insensible à la casse sur la colonne 'proprietes'
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . $q . '%']); // Recherche insensible à la casse sur la colonne 'description'
            });

            $query->orWhereHas('sous_categorie_info', function ($query) use ($q) {
                $query->whereRaw('LOWER(titre) LIKE ?', ['%' . $q . '%']); // Recherche insensible à la casse sur la relation 'sous_categorie_info'
            });
        }




        if ($sous_categorie) {
            $query->where('id_sous_categorie', $sous_categorie);

            $sous_cat = sous_categories::select("proprietes", "id_categorie")->find($sous_categorie);
            if ($sous_cat) {
                $categorie = $sous_cat->categorie->id;
                $ArrayProprietes = [];
                foreach ($sous_cat->proprietes as $propriete) {
                    $proprietes = proprietes::select("options", "nom")->find($propriete);
                    if ($proprietes) {
                        $optionsArray = [];
                        foreach ($proprietes->options ?? [] as $pro) {
                            $optionsArray[] = [
                                "nom" => $pro
                            ];
                        }
                        if (!empty($optionsArray)) {
                            $ArrayProprietes[] = [
                                "nom" => $proprietes->nom,
                                "options" => $optionsArray
                            ];
                        }
                    }
                }
            }
        }


        if ($categorie) {
            $id_categorie = $categorie;
            $query->whereHas('sous_categorie_info.categorie', function ($query) use ($id_categorie) {
                $query->where('id', $id_categorie);
            });
        }



        if ($etat) {
            $query->where('etat', $etat);
        }

        $posts = $query->paginate(24);
        foreach ($posts as $post) {
            // Vérifie si la première photo existe, sinon utilise une image par défaut
            $photo = isset($post->photos[0]) ? Storage::url($post->photos[0]) : "/icons/no-image.jpg";

            $subCardPostHtml = view('components.sub-card-post', ['post' => $post, 'show' => true])->render();

            $html .= '<div class="col-xl-4 col-lg-4 col-md-6 col-6">
                <div class="product_grid card b-0">
                    <div class="card-body p-0">
                        <div class="shop_thumb position-relative">
                        <button type="button" class="badge badge-like-post-count btn-like-post position-absolute ab-right cusor " id="post-' . $post->id . '" data-post-id="' . $post->id . '" onclick="btn_like_post(' . $post->id . ')">
                                <i class="bi bi-suit-heart-fill "></i>
                            <span class="count">
                                ' . $post->getLike->count() . '
                            </span>
                        </button>
                            <a class="card-img-top d-block overflow-hidden" href="/post/' . $post->id . '">
                                <img src="' . $photo . '" alt="..."></a>
                        </div>
                    </div>
                    ' . $subCardPostHtml . '
                </div>
            </div>';
        }




        return response()
            ->json(
                [
                    'count_resultat' => $posts->count(),
                    "total" => $total,
                    "html" => $html,
                    "data" => $posts,
                ]
            );
    }
}
