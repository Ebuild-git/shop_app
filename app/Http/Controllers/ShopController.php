<?php

namespace App\Http\Controllers;

use App\Models\posts;
use App\Models\User;

use App\Models\proprietes;
use App\Models\sous_categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    //controller dedier a la recherche

    public function index(Request $request)
    {

        ///////////- blog brouillons
        $Taille = strtolower($request->input('Taille')) ?? null;
        $Couleur = strtolower($request->input('Couleur')) ?? null;
        $ArticlePour = strtolower($request->input('ArticlePour')) ?? null;
        $Tailleenchiffre = strtolower($request->input('Tailleenchiffre')) ?? null;
        $Pointure = strtolower($request->input('Pointure')) ?? null;
        $Langue = strtolower($request->input('Langue')) ?? null;
        $Matiere = strtolower($request->input('Matiere')) ?? null;
        $PointureBeBe = strtolower($request->input('PointureBeBe')) ?? null;
        $TailleBeBe = strtolower($request->input('TailleBeBe')) ?? null;
        $PointureEnfant = strtolower($request->input('PointureEnfant')) ?? null;
        $TailleEnfant = strtolower($request->input('TailleEnfant')) ?? null;
        $MatiereSac = strtolower($request->input('MatiereSac')) ?? null;
        ///// fin du brouillons

        $gouvernorat = $request->input('gouvernorat') ?? null;
        $region = $request->input('region') ?? null;
        $proprietes = $request->input('proprietes' ?? []);
        $sous_categorie = $request->input('sous_categorie') ?? null;
        $ordre_prix = $request->input('ordre_prix') ?? null;
        $ordre_creation = $request->input('ordre_creation') ?? null;
        $categorie = $request->input('categorie') ?? null;
        $key = $request->input('key') ?? null;
        $etat = $request->input('etat') ?? null;
        $luxury_only = $request->input('check_luxury') ?? null;
        $html = "";

        $total = posts::whereIn('statut', ['vente', 'vendu'])->count();

        $usersWithVoyageMode = User::where('voyage_mode', true)->pluck('id');

        $query = posts::whereNotNull('verified_at')->select('titre', 'description', 'id_sous_categorie', 'prix', 'proprietes', 'photos', 'id', 'statut')
            ->whereIn('statut', ['vente', 'vendu'])
            ->whereNotIn('statut', ['livraison', 'livré', 'refusé'])
            ->whereNotIn('id_user', $usersWithVoyageMode)
            ->whereNull('deleted_at');
        if ($luxury_only == "true") {
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
            switch ($ordre_prix) {
                case 'Desc':
                    $query->orderBy('prix', 'DESC');
                    break;
                case 'Asc':
                    $query->orderBy('prix', 'ASC');
                    break;
                case 'Soldé':
                    $query->whereHas('changements_prix', function ($q) {
                        $q->whereNotNull('id'); // Ensuring at least one price change
                    });
                    break;
                case 'Luxury':
                    $query->whereHas('sous_categorie_info.categorie', function ($q) {
                        $q->where('luxury', true); // Filter for luxury items
                    });
                    break;
            }
        } else {
            $query->orderBy('id', 'DESC'); // Default order if no specific filter is selected
        }


        if ($gouvernorat) {
            $query->where('gouvernorat', $gouvernorat);
        }


        ///////////- blog brouillons
        if ($Taille) {
            $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(proprietes, '$.Taille'))) = ?", [$Taille]);
        }

        if ($Couleur) {
            $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(proprietes, '$.Couleur'))) = ?", [$Couleur]);
        }
        if ($ArticlePour) {
            $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(proprietes, '$.\"Article pour\"'))) = ?", [$ArticlePour]);
        }
        if ($Tailleenchiffre) {
            $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(proprietes, '$.\"Taille en chiffre\"'))) = ?", [$Tailleenchiffre]);
        }
        if ($Pointure) {
            $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(proprietes, '$.Pointure'))) = ?", [$Pointure]);
        }
        if ($Matiere) {
            $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(proprietes, '$.\"Matière de chaussures\"'))) = ?", [$Matiere]);
        }

        if ($MatiereSac) {
            $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(proprietes, '$.Matière'))) = ?", [$MatiereSac]);
        }
        if ($PointureBeBe) {
            $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(proprietes, '$.\"Pointure Bébé\"'))) = ?", [$PointureBeBe]);
        }
        if ($PointureEnfant){
            $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(proprietes, '$.\"Pointure Enfant\"'))) = ?", [$PointureEnfant]);
        }
        if ($TailleBeBe) {
            $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(proprietes, '$.\"Taille Bébé\"'))) = ?", [$TailleBeBe]);
        }

        if ($TailleEnfant) {
            $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(proprietes, '$.\"Taille Enfant\"'))) = ?", [$TailleEnfant]);
        }
        if ($Langue) {
            $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(proprietes, '$.\"Langue du livre\"'))) = ?", [$Langue]);
        }
        ///// fin du blog

        if ($key) {
            $q = strtolower($key);
            $colors = [
                'blue' => '#0000FF',
                'red' => '#FF0000',
                'green' => '#00FF00',
                'black' => '#000000',
                'argenté' => '#C0C0C0',
                'silver' => '#C0C0C0',
                'beige' => '#F5F5DC',
                'beige' => '#F5F5DC',
                'blanc' => '#FFFFFF',
                'white' => '#FFFFFF',
                'bleu-vert' => '#008080',
                'blue-green' => '#008080',
                'bordeaux' => '#800000',
                'bordeaux' => '#800000',
                'camel' => '#C19A6B',
                'camel' => '#C19A6B',
                'corail' => '#FF7F50',
                'coral' => '#FF7F50',
                'doré' => '#FFD700',
                'gold' => '#FFD700',
                'fushia' => '#FF00FF',
                'fuchsia' => '#FF00FF',
                'gris' => '#808080',
                'grey' => '#808080',
                'jaune' => '#FFFF00',
                'yellow' => '#FFFF00',
                'marron' => '#6F4F28',
                'brown' => '#6F4F28',
                'noir' => '#000000',
                'black' => '#000000',
                'nude' => '#F0E1D2',
                'nude' => '#F0E1D2',
                'orange' => '#FFA500',
                'orange' => '#FFA500',
                'rose' => '#FFC0CB',
                'pink' => '#FFC0CB',
                'rouge' => '#FF0000',
                'red' => '#FF0000',
                'turquoise' => '#40E0D0',
                'turquoise' => '#40E0D0',
                'taupe' => '#483C32',
                'taupe' => '#483C32',
                'vert' => '#008000',
                'green' => '#008000',
                'violet' => '#800080',
                'purple' => '#800080',
                'multicolore' => '#F5A9A9',
                'multicolor' => '#F5A9A9',
            ];
            $query->where(function ($query) use ($q, $colors) {
                $query->whereRaw('LOWER(titre) LIKE ?', ['%' . $q . '%'])
                    ->orWhereRaw('LOWER(proprietes) LIKE ?', ['%' . $q . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . $q . '%'])
                    ->orWhereRaw('LOWER(etat) LIKE ?', ['%' . $q . '%']);

                    foreach ($colors as $colorName => $hexCode) {
                        if (str_contains($q, $colorName) || str_contains($q, $hexCode)) {
                            $query->orWhereRaw('LOWER(proprietes) LIKE ?', ['%' . $hexCode . '%']);
                        }
                    }

                    if (str_contains(strtolower($q), 'soldé') || str_contains(strtolower($q), 'solde')) {
                        $query->orWhereNotNull('updated_price_at');
                    }
                    if (str_contains($q, 'luxury') || str_contains($q, 'luxe')) {
                        $query->orWhereHas('sous_categorie_info', function ($query) {
                            $query->whereHas('categorie', function ($query) {
                                $query->where('luxury', true);
                            });
                        });
                    }
                });

            $query->orWhereHas('sous_categorie_info', function ($query) use ($q) {
                $query->whereRaw('LOWER(titre) LIKE ?', ['%' . strtolower($q) . '%'])
                ->orWhereRaw('LOWER(title_en) LIKE ?', ['%' . strtolower($q) . '%'])
                ->orWhereRaw('LOWER(title_ar) LIKE ?', ['%' . strtolower($q) . '%']);
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
            if ($post->statut == "vente" || $post->statut == "vendu") {
                // Calculate discount percentage if there is a price change
                $originalPrice = $post->getOldPrix();
                $currentPrice = $post->getPrix();
                $discountPercentage = null;

                if ($originalPrice && $originalPrice > $currentPrice) {
                    $discountPercentage = round((($originalPrice - $currentPrice) / $originalPrice) * 100);
                }

                // Pass the discount percentage to the view
                $subCardPostHtml = view('components.sub-card-post', [
                    'post' => $post,
                    'show' => true,
                    'discountPercentage' => $discountPercentage
                ])->render();

                $url = "/post/" . $post->id . "/" . Str::slug($post->titre);
                $photo = isset($post->photos[0]) ? Storage::url($post->photos[0]) : "/icons/no-image.jpg";

                $html .= '<div class="col-xl-4 col-lg-4 col-md-6 col-6">
                <div class="product_grid card b-0">
                    <div class="card-body p-0">
                        <div class="shop_thumb position-relative">

                            <!-- Discount & Vendu Badges -->
                            <div class="badge-container position-absolute top-0 start-0 d-flex gap-4 mobile-display-luxe" style="z-index: 5;">'
                            . ($discountPercentage ? '
                                <div class="badge-new badge-discount">-' . $discountPercentage . '%</div>' : '')
                            . ($post->statut === 'vendu' ? '
                                <div class="badge-new badge-sale bg-danger text-white">' . \App\Traits\TranslateTrait::TranslateText('vendu') . '</div>' : '') .
                            '</div>

                            <!-- Like Button -->
                            <button type="button" class="badge badge-like-post-count btn-like-post position-absolute ab-right cusor"
                                id="post-' . $post->id . '" data-post-id="' . $post->id . '" onclick="btn_like_post(' . $post->id . ')">
                                <i class="bi bi-suit-heart-fill"></i>
                                <span class="count">' . $post->getLike->count() . '</span>
                            </button>

                            <!-- Product Image -->
                            <a class="card-img-top d-block overflow-hidden" href="' . $url . '">
                                <img src="' . $photo . '" alt="...">
                            </a>
                        </div>
                    </div>
                    ' . $subCardPostHtml . '
                </div>
            </div>';

            }
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
