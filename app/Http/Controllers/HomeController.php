<?php

namespace App\Http\Controllers;

use App\Events\AdminEvent;
use App\Events\UserEvent;
use App\Mail\VerifyMail;
use App\Models\categories;
use App\Models\configurations;
use App\Models\favoris;
use App\Models\likes;
use App\Models\notifications;
use App\Models\posts;
use App\Models\ratings;
use App\Models\regions;
use App\Models\OrdersItem;
use App\Models\signalements;
use App\Models\sous_categories;
use App\Models\User;
use App\Models\UserBlock;
use App\Models\UserCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\City;

class HomeController extends Controller
{
    public function index()
    {
        $categories = categories::all();
        $configuration = configurations::firstOrCreate();
        $usersWithVoyageMode = User::where('voyage_mode', true)->pluck('id');
        // Fetch non-luxury posts
        $last_post = posts::join('sous_categories', 'posts.id_sous_categorie', '=', 'sous_categories.id')
            ->join('categories', 'sous_categories.id_categorie', '=', 'categories.id')
            ->join('users', 'posts.id_user', '=', 'users.id')
            ->whereNull('users.deleted_at')
            ->where('categories.luxury', false)
            ->whereNotIn('statut', ['validation'])
            ->whereNotIn('id_user', $usersWithVoyageMode)
            ->whereNotBlocked(Auth::id())
            ->select('posts.id', 'posts.photos', 'posts.prix', 'posts.old_prix', 'posts.statut', 'posts.sell_at')
            ->orderBy('posts.created_at', 'Desc')
            ->orderBy('posts.updated_price_at', 'Desc')
            ->take(12)
            ->get();
        // Fetch luxury posts
        $luxurys = posts::join('sous_categories', 'posts.id_sous_categorie', '=', 'sous_categories.id')
            ->join('categories', 'sous_categories.id_categorie', '=', 'categories.id')
            ->join('users', 'posts.id_user', '=', 'users.id')
            ->whereNull('users.deleted_at')
            ->where('categories.luxury', true)
            ->whereNotIn('statut', ['validation'])
            ->whereNotBlocked(Auth::id())
            ->orderBy('posts.created_at', 'Desc')
            ->select('posts.id', 'posts.photos', 'posts.prix', 'posts.old_prix', 'posts.statut', 'posts.sell_at')
            ->take(12)->get();

        // Calculate the discount percentage for each post
        foreach ($last_post as $post) {
            $post->discountPercentage = null;
            if ($post->old_prix && $post->old_prix > $post->prix) {
                $post->discountPercentage = round((($post->old_prix - $post->prix) / $post->old_prix) * 100);
            }
        }

        foreach ($luxurys as $lux) {
            $lux->discountPercentage = null;
            if ($lux->old_prix && $lux->old_prix > $lux->prix) {
                $lux->discountPercentage = round((($lux->old_prix - $lux->prix) / $lux->old_prix) * 100);
            }
        }
        $categories_carousel = Categories::where('active', true)->orderBy('order')->get();

        return view('User.index', compact('categories', 'configuration', 'last_post', 'luxurys', 'categories_carousel'));
    }

    public function index_post(Request $request)
    {
        $id = $request->id ?? '';
        $step = $request->input('step', 1);

        return view('User.post', compact('id', 'step'));
    }

    // public function checkCinStatus()
    // {
    //     if (!Auth::check()) {
    //         return response()->json(['locked' => true, 'unauthenticated' => true]);
    //     }

    //     $user = Auth::user();

    //     if ($user->locked) {
    //         return response()->json(['locked' => true]);
    //     }

    //     $hasCin = $user->cin_img ? true : false;
    //     $approved = $user->cin_approved ? true : false;

    //     if ($hasCin && !$approved) {
    //         $existingRappel = notifications::where('id_user', $user->id)
    //             ->where('type', 'rappel_cin')
    //             ->where('destination', 'admin')
    //             ->where('created_at', '>=', now()->subDays(1))
    //             ->first();

    //         if (!$existingRappel) {
    //             $admin = User::where('role', 'admin')->first();
    //             if ($admin) {
    //                 $notification = new notifications();
    //                 $notification->titre = 'Rappel validation CIN';
    //                 $notification->id_user_destination = $admin->id;
    //                 $notification->id_user = $user->id;
    //                 $notification->type = 'rappel_cin';
    //                 $notification->destination = 'admin';
    //                 $notification->url = '/admin/client/' . $user->id . '/view';
    //                 $notification->message = 'L\'utilisateur ' . $user->username . ' attend la validation de son CIN depuis longtemps.';
    //                 $notification->save();
    //             }
    //         }
    //     }

    //     return response()->json([
    //         'locked' => false,
    //         'has_cin' => $hasCin,
    //         'approved' => $approved,
    //     ]);
    // }
    public function checkCinStatus()
    {
        if (!Auth::check()) {
            return response()->json(['locked' => true, 'unauthenticated' => true]);
        }

        $user = Auth::user();

        if ($user->locked) {
            return response()->json(['locked' => true]);
        }

        $hasCin  = $user->cin_img  ? true : false;
        $hasCin2 = $user->cin_img2 ? true : false;
        $hasBothCin = $hasCin && $hasCin2;
        $approved = $user->cin_approved ? true : false;

        if ($hasBothCin && !$approved) {
            $existingRappel = notifications::where('id_user', $user->id)
                ->where('type', 'rappel_cin')
                ->where('destination', 'admin')
                ->where('created_at', '>=', now()->subDays(1))
                ->first();

            if (!$existingRappel) {
                $admin = User::where('role', 'admin')->first();
                if ($admin) {
                    $notification = new notifications();
                    $notification->titre = 'Rappel validation CIN';
                    $notification->id_user_destination = $admin->id;
                    $notification->id_user = $user->id;
                    $notification->type = 'rappel_cin';
                    $notification->destination = 'admin';
                    $notification->url = '/admin/client/' . $user->id . '/view';
                    $notification->message = 'L\'utilisateur ' . $user->username . ' attend la validation de son CIN depuis longtemps.';
                    $notification->save();
                }
            }
        }

        return response()->json([
            'locked'      => false,
            'has_cin'     => $hasCin,
            'has_cin2'    => $hasCin2,
            'has_both_cin' => $hasBothCin,
            'approved'    => $approved,
        ]);
    }

    public function showRibForm(Request $request)
    {
        $step = $request->input('step', 1);

        return view('rib-form', compact('step'));
    }

    public function submitRib(Request $request)
    {
        $request->validate([
            'ribNumber'     => 'nullable|string|digits:24',
            'bankName'      => 'nullable|string',
            'titulaireName' => 'nullable|string',
            'cin_img'       => 'nullable|image|mimes:jpg,png,jpeg,webp|max:10048',
            'cin_img2'      => 'nullable|image|mimes:jpg,png,jpeg,webp|max:10048',
        ], [
            'ribNumber.digits' => __('validation.rib_digits'),
        ]);

        $user = Auth::user();
        $isNew = empty($user->rib_number);

        $currentDecrypted = null;
        try {
            $currentDecrypted = $user->rib_number ? Crypt::decryptString($user->rib_number) : null;
        } catch (\Exception $e) {
            $currentDecrypted = $user->rib_number;
        }

        $user->rib_number     = Crypt::encryptString($request->input('ribNumber'));
        $user->bank_name      = $request->input('bankName');
        $user->titulaire_name = $request->input('titulaireName');

        if ($currentDecrypted !== $request->input('ribNumber')) {
            event(new AdminEvent('Un utilisateur a mis à jour son RIB.'));
            $notification              = new notifications();
            $notification->type        = 'photo';
            $notification->titre       = $user->username . ' a mis à jour son RIB.';
            $notification->url         = '/admin/client/' . $user->id . '/view';
            $notification->message     = 'RIB en attente de vérification.';
            $notification->id_user     = $user->id;
            $notification->destination = 'admin';
            $notification->save();
        }

        // Track which CIN sides were updated for a single notification
        $cinChanged = [];

        // --- CIN Recto ---
        if ($request->hasFile('cin_img')) {
            if ($user->cin_img) {
                $oldCinImages   = json_decode($user->old_cin_images, true) ?? [];
                $oldCinImages[] = $user->cin_img;
                $user->old_cin_images = json_encode($oldCinImages);
            }
            $user->cin_img      = $request->file('cin_img')->store('cin_images', 'public');
            $user->cin_approved = false;
            $cinChanged[]       = 'Recto';
        }

        // --- CIN Verso ---
        if ($request->hasFile('cin_img2')) {
            if ($user->cin_img2) {
                $oldCinImages   = json_decode($user->old_cin_images, true) ?? [];
                $oldCinImages[] = $user->cin_img2;
                $user->old_cin_images = json_encode($oldCinImages);
            }
            $user->cin_img2     = $request->file('cin_img2')->store('cin_images', 'public');
            $user->cin_approved = false;
            $cinChanged[]       = 'Verso';
        }

        // Single notification covering both sides
        if (!empty($cinChanged)) {
            $cinLabel = implode(' & ', $cinChanged);
            event(new AdminEvent('Un utilisateur a mis à jour sa carte d\'identité.'));
            $notification              = new notifications();
            $notification->type        = 'photo';
            $notification->titre       = $user->username . " a mis à jour sa carte d'identité ({$cinLabel}).";
            $notification->url         = '/admin/client/' . $user->id . '/view';
            $notification->message     = "Carte d'identité ({$cinLabel}) en attente de validation.";
            $notification->id_user     = $user->id;
            $notification->destination = 'admin';
            $notification->save();
        }

        $user->save();

        $message = $isNew
            ? __('Informations ajoutées avec succès.')
            : __('Informations modifiées avec succès.');

        return response()->json(['message' => $message]);
    }

    public function index_mes_post(Request $request)
    {
        $month = $request->input('month') ?? null;
        $year = $request->input('year') ?? null;
        $type = $request->get('type') ?? 'annonce';
        $statut = $request->input('statut') ?? null;
        $key = $request->input('key') ?? null;
        $Query = posts::where('id_user', Auth::user()->id);

        if ($key) {
            $Query->where(function ($q) use ($key) {
                $q->where('titre', 'LIKE', "%{$key}%")
                ->orWhere('description', 'LIKE', "%{$key}%");
            });
        }

        if ($type != 'annonce') {
            $type = 'vente';
        }
        if ($type == 'vente') {
            $Query->whereNotNull('sell_at')
                ->whereIn('statut', ['vendu', 'livraison', 'préparation', 'en cours de livraison', 'ramassée', 'livré', 'retourné', 'commande confirmée','tentative de livraison','retourné à l\'expéditeur','annulé','livraison retardée','ramassage planifié','reprogrammé']);
                // ->whereDoesntHave('orderItems', function ($q) {
                //     $q->onlyTrashed();
                // });

            if ($month && $year) {
                $Query->whereYear('sell_at', $year)
                    ->whereMonth('sell_at', $month);
            }
            $Query->orderBy('sell_at', 'desc');
        } else {

            if ($month && $year) {
                $Query->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month);
            }
            $Query->withTrashed();
            $Query->orderBy('created_at', 'desc');
        }

        if (!empty($statut)) {
            switch ($statut) {
                case 'validation':
                    $Query->where('statut', 'validation');
                    break;
                case 'vente':
                    $Query->where(function ($query) {
                        $query->where('statut', 'vente')
                            ->orWhere(function ($subQuery) {
                                $subQuery->where('verified_at', '!=', null)
                                    ->where('sell_at', null)
                                    ->whereHas('user_info', function ($userQuery) {
                                        $userQuery->where('voyage_mode', 0); // voyage_mode should be false (0)
                                    });
                            });
                    });
                    break;
                case 'vendu':
                    $Query->where('statut', 'vendu')
                        ->orWhere('sell_at', '!=', null);
                    break;
                case 'livraison':
                    $Query->where('statut', 'livraison');
                    break;
                case 'livré':
                    $Query->where('statut', 'livré');
                    break;
                case 'refusé':
                    $Query->where('statut', 'refusé');
                    break;
                case 'préparation':
                    $Query->where('statut', 'préparation');
                    break;
                case 'en cours de livraison':
                    $Query->where('statut', 'en cours de livraison');
                    break;
                case 'ramassée':
                    $Query->where('statut', 'ramassée');
                    break;
                case 'retourné':
                    $Query->where('statut', 'retourné');
                    break;
                case 'en voyage':
                    $Query->where(function ($query) {
                        $query->where('statut', 'en voyage')
                            ->orWhere(function ($subQuery) {
                                $subQuery->whereHas('user_info', function ($userQuery) {
                                    $userQuery->where('voyage_mode', 1);
                                })
                                    ->where('verified_at', '!=', null)
                                    ->where('sell_at', null);
                            });
                    });
                    break;
                default:
                    // Handle unknown status or fallback
                    $Query->where('statut', 'like', "%{$statut}%");
                    break;
            }
        }

        $posts = $Query->paginate('20');

        return view('User.list_post', [
            'posts' => $posts,
            'year' => $year,
            'month' => $month,
            'statut' => $statut,
            'type' => $type,
            'key' => $key,
            'currentPage' => $posts->currentPage(),
            'lastPage' => $posts->lastPage(),
            'nextPageUrl' => $posts->nextPageUrl(),
            'previousPageUrl' => $posts->previousPageUrl(),
            'totalItems' => $posts->total(),
        ]);
    }

    public function details_post($id)
    {
        $post = posts::whereNotBlocked(Auth::id())->find($id);
        if (!$post) {
            abort(404);
        }
        $user = $post->user_info;
        $ipAddress = request()->ip();
        $viewedIps = json_decode($post->ip_address, true) ?? [];
        $isOwner = Auth::check() && Auth::id() === $post->id_user;

        if (!$isOwner && !in_array($ipAddress, $viewedIps)) {
            $post->increment('views');
            $viewedIps[] = $ipAddress;
            $post->ip_address = json_encode($viewedIps);
            $post->save();
        }

        if (Auth::check()) {
            $isFavorited = favoris::where('id_post', $post->id)->where('id_user', Auth::user()->id)->exists();
            $isLiked = likes::where('id_post', $post->id)->where('id_user', Auth::user()->id)->exists();
        } else {
            $isFavorited = false;
            $isLiked = false;
        }

        if (Auth::check()) {
            $is_alredy_signaler = signalements::where('id_user_make', Auth::user()->id)
                ->where('id_post', $post->id)->count();
        }

        $produit_in_cart = false;

        if (Auth::check()) {
            $produit_in_cart = UserCart::where('user_id', Auth::id())
                ->where('post_id', $post->id)
                ->exists();
        }

        if (!$produit_in_cart) {
            $cart = json_decode($_COOKIE['cart'] ?? '[]', true);
            $produit_in_cart = collect($cart)->contains('id', $post->id);
        }

        $ma_note = null;
        if (Auth::check()) {
            $ma_note = ratings::where('id_user_sell', $user->id)->avg('etoiles');
        }

        $usersWithVoyageMode = User::where('voyage_mode', true)->pluck('id');

        $other_product = posts::where('id_sous_categorie', $post->id_sous_categorie)
            ->select('photos', 'id', 'id_user')
            ->where('verified_at', '!=', null)
            ->where('statut', '!=', 'validation')
            ->where('id_user', '!=', Auth::id())
            ->whereNotBlocked(Auth::id())
            ->whereNotIn('id_user', $usersWithVoyageMode)
            ->inRandomOrder()
            ->take(16)
            ->get();
        $user_product = posts::where('id_user', $post->id_user)
            ->select('photos', 'id', 'sell_at', 'id_user')
            ->where('verified_at', '!=', null)
            ->where('statut', '!=', 'validation')
            ->whereNotBlocked(Auth::id())
            ->inRandomOrder()
            ->take(16)
            ->get();

        $post->discountPercentage = null;
        if ($post->changements_prix->isNotEmpty()) {
            $firstChange = $post->changements_prix->first();
            $old_raw = (float) $firstChange->old_price;
            $new_raw = (float) $firstChange->new_price;

            if ($old_raw && $old_raw > $new_raw) {
                $post->discountPercentage = (int) round(
                    (($old_raw - $new_raw) / $old_raw) * 100
                );
            }
        }
        return view('User.details')
            ->with('post', $post)
            ->with('user', $user)
            ->with('isFavorited', $isFavorited)
            ->with('isLiked', $isLiked)
            ->with('other_products', $other_product)
            ->with('user_products', $user_product)
            ->with('ma_note', $ma_note)
            ->with('is_alredy_signaler', $is_alredy_signaler ?? false)
            ->with('produit_in_cart', $produit_in_cart);
    }

    public function user_profile($id)
    {

        $user = User::find($id);

        // Check if the user is in voyage mode
        if ($user->voyage_mode) {
            $posts = collect();
        } else {
            $posts = posts::where('id_user', $user->id)
                ->where('statut', '!=', 'validation')
                ->orderBy('created_at', 'desc')
                ->get()->map(function ($post) {
                    $post->discountPercentage = null;
                    if ($post->old_prix && $post->old_prix > $post->prix) {
                        $post->discountPercentage = round((($post->old_prix - $post->prix) / $post->old_prix) * 100);
                    }

                    return $post;
                });
        }

        $notes = ratings::where('id_user_sell', $user->id)->avg('etoiles');
        $ma_note = ratings::where('id_user_sell', $user->id)->avg('etoiles');
        $count = number_format($user->averageRating->average_rating ?? 1);
        $avis = $user->getReviewsAttribute->count();
        $total_views = posts::where('id_user', $user->id)->sum('views');

         // Check if the auth user has blocked this shopiner (for UI indicator)
        $isBlocked = false;
        if (Auth::check() && Auth::id() != $user->id) {
            $isBlocked = UserBlock::where('blocker_id', Auth::id())
                ->where('blocked_id', $user->id)
                ->exists();
        }

        // Fetch posts, filtering out blocked users from the viewer's perspective
        if ($user->voyage_mode) {
            $posts = collect();
        } else {
            $posts = posts::where('id_user', $user->id)
                ->where('statut', '!=', 'validation')
                ->whereNotBlocked(Auth::id())
                ->orderBy('created_at', 'desc')
                ->get()->map(function ($post) {
                    $post->discountPercentage = null;
                    if ($post->old_prix && $post->old_prix > $post->prix) {
                        $post->discountPercentage = round((($post->old_prix - $post->prix) / $post->old_prix) * 100);
                    }
                    return $post;
                });
        }

        return view('User.profile')
            ->with('user', $user)
            ->with('posts', $posts)
            ->with('notes', $notes)
            ->with('ma_note', $ma_note)
            ->with('count', $count)
            ->with('avis', $avis)
            ->with('isBlocked', $isBlocked);
    }

    public function blockUser($id)
    {
        if (Auth::id() == $id) {
            return response()->json(['message' => 'Vous ne pouvez pas vous bloquer vous-même'], 422);
        }

        UserBlock::firstOrCreate([
            'blocker_id' => Auth::id(),
            'blocked_id' => $id,
        ]);

        return response()->json(['message' => 'Utilisateur bloqué avec succès', 'status' => 'blocked']);
    }

    public function unblockUser($id)
    {
        UserBlock::where('blocker_id', Auth::id())
            ->where('blocked_id', $id)
            ->delete();

        return response()->json(['message' => 'Utilisateur débloqué avec succès', 'status' => 'unblocked']);
    }

    public function informations()
    {
        return view('User.infromations');
    }

    public function modeVoyage()
    {
        return view('User.mode_voyage');
    }

    public function historiques($type)
    {
        $AllowedType = ['achats', 'ventes', 'annonces'];
        if (!in_array($type, $AllowedType)) {
            $type = 'achats';
        }

        $count = posts::where('id_user', Auth::user()->id)->count();
        $showRemainingTimeColumn = $type == 'ventes';

        if ($type == 'achats') {
            $achats = OrdersItem::whereHas('order', function ($q) {
                    $q->where('buyer_id', Auth::id());
                })
                ->with([
                    'post' => function ($q) {
                        $q->withTrashed()
                        ->with(['changements_prix', 'user_info' => fn($q) => $q->withTrashed()]);
                    },
                    'order',
                ])
                ->whereHas('post', fn($q) => $q->withTrashed())
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return view('User.historiques', compact('type', 'count', 'achats'));
        }
        if ($type == 'ventes') {
            $ventes = posts::where('id_user', Auth::user()->id)
                ->whereNotNull('sell_at')
                ->whereIn('statut', ['vendu', 'livraison', 'préparation', 'en cours de livraison', 'ramassée', 'livré', 'retourné', 'commande confirmée','tentative de livraison','retourné à l\'expéditeur','annulé','livraison retardée','ramassage planifié','reprogrammé'])
                ->whereDoesntHave('orderItems', function ($q) {
                    $q->onlyTrashed();
                })
                ->orderBy('sell_at', 'desc')
                ->paginate(20);

            $currentPage     = $ventes->currentPage();
            $lastPage        = $ventes->lastPage();
            $nextPageUrl     = $ventes->nextPageUrl();
            $previousPageUrl = $ventes->previousPageUrl();
            $totalItems      = $ventes->total();

            return view('User.historiques', compact(
                'type', 'count', 'ventes', 'showRemainingTimeColumn',
                'currentPage', 'lastPage', 'nextPageUrl', 'previousPageUrl', 'totalItems'
            ));
        }

        if ($type == 'annonces') {
            $annonces = posts::where('id_user', Auth::user()->id)
                ->withTrashed()
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            $currentPage     = $annonces->currentPage();
            $lastPage        = $annonces->lastPage();
            $nextPageUrl     = $annonces->nextPageUrl();
            $previousPageUrl = $annonces->previousPageUrl();
            $totalItems      = $annonces->total();

            return view('User.historiques', compact(
                'type', 'count', 'annonces', 'showRemainingTimeColumn',
                'currentPage', 'lastPage', 'nextPageUrl', 'previousPageUrl', 'totalItems'
            ));
        }
    }

    public function inscription_post(Request $request)
    {
        $forbiddenWord = 'shopin';
        $forbiddenFields = ['email', 'username', 'nom', 'prenom'];
        $fieldLabels = [
            'email'    => __('email'),
            'username' => __('pseudonyme'),
            'nom'      => __('nom'),
            'prenom'   => __('prenom'),
        ];

        // ← was using $requestData (undefined), now uses $request
        foreach ($forbiddenFields as $field) {
            if (stripos($request->input($field, ''), $forbiddenWord) !== false) {
                return redirect()->back()->with('error', __('error.forbidden_word', [
                    'word'  => $forbiddenWord,
                    'field' => $fieldLabels[$field] ?? $field,
                ]))->withInput();
            }
        }

        $validator = Validator::make($request->all(), [
            'email'          => 'required|email|unique:users,email',
            'password'       => [
                'required', 'confirmed', 'string', 'min:8',
                'regex:/[a-z]/', 'regex:/[A-Z]/',
                'regex:/[0-9]/', 'regex:/[@$!%*#?&]/',
            ],
            'photo'          => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048',
            'matricule'      => 'nullable|mimes:jpg,png,jpeg,pdf|max:2048',
            'nom'            => ['required', 'string'],
            'prenom'         => ['required', 'string'],
            'region'         => 'required|exists:regions,id',
            'city_id'        => 'required|exists:cities,id',
            'telephone'      => ['nullable', 'string', 'max:15'],
            'username'       => ['required', 'string', 'unique:users,username'],
            'genre'          => 'required|in:female,male,prefer_not_to_say',
            'jour'           => 'nullable|integer|between:1,31',
            'mois'           => 'nullable|integer|between:1,12',
            'annee'          => 'nullable|integer|between:1950,' . date('Y'),
            'ruee'           => ['required', 'string'],
            'nom_batiment'   => ['required', 'string'],
            'etage'          => ['required'],
            'num_appartement'=> ['required', 'string'],
        ], [
            'required'                  => __('validation.required'),
            'city_id.required'          => __('validation.city_required'),
            'ruee.required'             => __('validation.street_required'),
            'username.unique'           => __('error.username_exists'),
            'username.required'         => __('validation.required'),
            'email.unique'              => __('error.email_exists'),
            'string'                    => __('error.invalid_type'),
            'password.min'              => __('validation.password.min'),
            'password.confirmed'        => __('validation.password.confirmed'),
            'password.regex'            => __('validation.password.regex'),
            'integer'                   => __('error.invalid_integer'),
            'genre.in'                  => __('error.gender_required'),
            'mimes'                     => __('error.invalid_file_type'),
            'image'                     => __('error.invalid_image'),
            'max'                       => __('error.max_size'),
            'between'                   => __('error.invalid_date'),
            'nom_batiment.required'     => __('validation.building_required'),
            'etage.required'            => __('validation.floor_required'),
            'num_appartement.required'  => __('validation.apartment_required'),
        ]);

        // ← Stop here if validation fails — nothing is created yet
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // ← Validate date before touching DB
        try {
            $date = \Carbon\Carbon::createFromDate($request->annee, $request->mois, $request->jour);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('error.invalid_date'))->withInput();
        }

        if ($date->diffInYears(\Carbon\Carbon::now()) < 18) {
            return redirect()->back()->with('error', __('error.age_limit'))->withInput();
        }

        // ← Only reach DB after ALL checks pass
        try {
            $result = DB::transaction(function () use ($request, $date) {
                $config = configurations::first();
                $token  = md5(time());

                $user = new User();
                $user->lastname        = $request->nom;
                $user->email           = $request->email;
                $user->firstname       = $request->prenom;
                $user->password        = Hash::make($request->password);
                $user->phone_number    = $request->telephone;
                $user->birthdate       = $date;
                $user->gender          = $request->genre;
                $user->role            = 'user';
                $user->type            = 'user';
                $user->city_id         = $request->city_id;
                $user->username        = $request->username;
                $user->ip_address      = request()->ip();
                $user->remember_token  = $token;
                $user->rue             = $request->ruee;
                $user->nom_batiment    = $request->nom_batiment;
                $user->etage           = $request->etage;
                $user->num_appartement = $request->num_appartement;
                $user->region          = $request->region;

                if ($request->hasFile('matricule')) {
                    $matricule  = $request->matricule->store('uploads/documents', 'public');
                    $user->type = 'shop';
                    $user->matricule = $matricule;
                }

                if ($request->hasFile('photo')) {
                    $path       = \App\Services\ImageService::uploadAndConvert($request->file('photo'), 'uploads/avatars');
                    $user->avatar = $path;
                    $user->photo_verified_at = $config->valider_photo == 1 ? null : now();
                } else {
                    $user->photo_verified_at = now();
                }

                $user->save();
                $user->assignRole('user');

                return [$user, $token, $config];
            });

            [$user, $token, $config] = $result;

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('error.registration_failed'))
                ->withInput();
        }

        // ← Only fires after successful DB save
        event(new AdminEvent("Un nouvel utilisateur s'est inscrit."));

        $notification = new notifications();
        $notification->type        = 'photo';
        $notification->titre       = 'Nouvel utilisateur : ' . $user->username;
        $notification->url         = '/admin/client/' . $user->id . '/view';
        $notification->message     = 'Un nouveau compte a été créé';
        $notification->id_user     = $user->id;
        $notification->destination = 'admin';
        $notification->save();

        if ($request->hasFile('photo') && $config->valider_photo == 1) {
            event(new AdminEvent('Un utilisateur a ajouté une photo de profil'));

            $photoNotification = new notifications();
            $photoNotification->type        = 'photo';
            $photoNotification->titre       = $user->username . ' a ajouté une photo de profil';
            $photoNotification->url         = '/admin/client/' . $user->id . '/view';
            $photoNotification->message     = 'Le client a ajouté une photo de profil en attente de validation';
            $photoNotification->id_user     = $user->id;
            $photoNotification->destination = 'admin';
            $photoNotification->save();
        }

        try {
            Mail::to($user->email)->send(new VerifyMail($user, $token));
        } catch (\Exception $e) {
            return redirect('/connexion')->with('error', __('error.email_send'));
        }

        return redirect('/connexion')->with('success', __('success.account_created'));
    }

    public function count_panier()
    {
        $user = Auth::user();
        $cartItems = UserCart::where('user_id', $user->id)->get();
        $produits = [];
        $montant = 0;
        $html = '';
        $validCount = 0;

        foreach ($cartItems as $item) {
            $produit = posts::where('id', $item->post_id)->where('id_user', '!=', $user->id)->where('statut', 'vente')->first();
            if ($produit) {
                $CartItem = view('components.cart-item', ['produit' => $produit])->render();
                $montant += $produit->getPrix();
                $html .= $CartItem;
                $validCount++;
            } else {
                $this->delete_form_cart($item->post_id);
            }
        }

        return response()->json(
            [
                'count' => $validCount,
                'produits' => $produits,
                'montant' => number_format($montant, 2, '.', '') . ' ' . __('currency'),
                'html' => $html,
                'statut' => true,
            ]
        );
    }

    public function remove_to_card(Request $request)
    {
        $id = $request->input('id') ?? '';

        if ($id) {
            $this->delete_form_cart($id);

            return response()->json([
                'status' => true,
                'message' => __('article_removed_from_cart'),
                'exist' => false,
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid product ID',
        ]);
    }

    public function add_panier(Request $request)
    {
        $id = $request->input('id') ?? '';
        $post = posts::where('id', $id)->where('statut', 'vente')->first();

        if (!$post) {
            return response()->json([
                'status' => true,
                'message' => "Cet article n'est plus disponible à la vente",
                'exist' => false,
            ]);
        }

        if ($post->id_user == Auth::user()->id) {
            return response()->json([
                'status' => true,
                'message' => 'Vous ne pouvez pas ajouter votre propre article dans votre panier',
                'exist' => false,
            ]);
        }

        $user = Auth::user();
        $productExists = UserCart::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->exists();

        if (!$productExists) {
            // Add the product to the `user_carts` table
            UserCart::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);

            return response()->json([
                'status' => false,
                'message' => __('article_added_successfully'),
                'exist' => true,
            ]);
        } else {
            // Remove the product from the `user_carts` table
            UserCart::where('user_id', $user->id)
                ->where('post_id', $post->id)
                ->delete();

            return response()->json([
                'status' => true,
                'message' => __('article_removed_from_cart'),
                'exist' => false,
            ]);
        }
    }

    public function like(Request $request)
    {
        $id_post = $request->input('id_post') ?? '';

        //verification de la connexion
        if (!Auth::check()) {
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Veuillez vous connecter',
                ]
            );
        }

        $post = posts::find($id_post);
        if (!$post) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Annonce introuvable',
                ]
            );
        }

        $liked = likes::where('id_post', $post->id)
            ->where('id_user', Auth::user()->id)
            ->exists();

        if ($liked === true) {
            likes::where('id_post', $post->id)
                ->where('id_user', Auth::user()->id)
                ->delete();

            return response()->json(
                [
                    'status' => false,
                    'liked' => false,
                    'message' => 'Vous avez retiré votre like .',
                ]
            );
        } else {
            likes::firstOrCreate(
                [
                    'id_post' => $post->id,
                    'id_user' => Auth::user()->id,
                ]
            );
            //make notification
            event(new UserEvent($post->id_user));
            $notification = new notifications();
            $notification->titre = Auth::user()->username . ' a aimé votre publication.';
            $notification->id_user_destination = $post->id_user;
            $notification->type = 'like';
            $notification->destination = 'user';
            $notification->url = '/post/' . $post->id;
            $notification->message = '@' . Auth::user()->username . " vient d'aimer votre publication";
            $notification->save();

            // Send FCM notification
            $fcmService = app(\App\Services\FcmService::class);
            $sent = $fcmService->sendToUser(
                $post->id_user,
                Auth::user()->username . ' a aimé votre publication',
                '@' . Auth::user()->username . " vient d'aimer votre publication",
                [
                    'type' => 'like',
                    'notification_id' => $notification->id,
                    'destination' => 'user',
                    'action' => 'post_liked',
                    'post_id' => $post->id,
                    'liker_username' => Auth::user()->username,
                ]
            );

            if ($sent) {
                \Log::info('FCM notification sent successfully', [
                    'user_id' => $post->id_user,
                    'notification_id' => $notification->id,
                    'type' => 'post_liked',
                ]);
            } else {
                \Log::warning('FCM notification failed to send', [
                    'user_id' => $post->id_user,
                    'notification_id' => $notification->id,
                    'reason' => 'User has no FCM token or token invalid',
                ]);
            }

            return response()->json(
                [
                    'status' => false,
                    'liked' => false,
                    'message' => 'Like ajouté !',
                ]
            );
        }
    }

    public function delete_form_cart($id)
    {
        $user = Auth::user();

        UserCart::where('user_id', $user->id)
            ->where('post_id', $id)
            ->delete();
    }

    public function conditions()
    {
        return view('User.faq.conditions');
    }

    public function inscription()
    {
        //check if user loged
        if (Auth::check()) {
            return redirect()->route('home');
        }

        $regions = regions::all();
        $cities = City::orderBy('name')->get();

        return view('User.Auth.inscription')
            ->with('regions', $regions)
            ->with('cities', $cities);
    }

    public function connexion()
    {
        //check if user loged
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('User.Auth.connexion');
    }

    public function forget()
    {
        return view('User.Auth.forget');
    }

    // public function index_mes_achats(Request $request)
    // {

    //     $month = $request->input('month') ?? null;
    //     $year = $request->input('year') ?? null;

    //     $query = posts::where('id_user_buy', Auth::id())
    //         ->select('titre', 'photos', 'id_sous_categorie', 'id_user', 'statut', 'prix', 'sell_at', 'id')
    //         ->orderBy('sell_at', 'desc');

    //     if ($month && $year) {
    //         $query->whereYear('sell_at', $year)
    //             ->whereMonth('sell_at', $month);
    //     }

    //     $achats = $query->paginate(20);
    //     $total = posts::where('id_user_buy', Auth::id())->count();

    //     return view('User.mes-achats')
    //         ->with('achats', $achats)
    //         ->with('month', $month)
    //         ->with('year', $year)
    //         ->with('total', $total);
    // }
    public function index_mes_achats(Request $request)
    {
        $month = $request->input('month') ?? null;
        $year  = $request->input('year') ?? null;

        $query = OrdersItem::whereHas('order', function ($q) {
                $q->where('buyer_id', Auth::id());
            })
            ->with([
                'post' => function ($q) {
                    $q->withTrashed()
                    ->with(['changements_prix', 'user_info' => fn($q) => $q->withTrashed()]);
                },
                'order',
            ])
            ->whereHas('post', fn($q) => $q->withTrashed())
            ->orderBy('created_at', 'desc');

        if ($month && $year) {
            $query->whereYear('created_at', $year)
                ->whereMonth('created_at', $month);
        }

        $achats = $query->paginate(20);
        $total  = OrdersItem::whereHas('order', fn($q) => $q->where('buyer_id', Auth::id()))
            ->count();

        return view('User.mes-achats')
            ->with('achats', $achats)
            ->with('month', $month)
            ->with('year', $year)
            ->with('total', $total);
    }

    public function checkout(Request $request)
    {
        $step = $request->get('step') ?? 1;

        if ($step < 1 || $step > 4) {
            return redirect()->route('checkout');
        }

        return view('User.checkout')
            ->with('step', $step)
            ->with('order_id', $request->get('order_id'));
    }

    public function about()
    {
        return view('User.about');
    }

    public function how_buy()
    {
        return view('User.faq.howtobuy');
    }

    public function how_sell()
    {
        return view('User.faq.faq');
    }

    public function contact()
    {
        $configuration = configurations::first();

        return view('User.contact', compact('configuration'));
    }

    public function blockedShopiners()
     {
         $blockedUsers = User::whereIn('id', Auth::user()->blockedUserIds())->get();
         return view('User.blocked_shopiners', compact('blockedUsers'));
     }

     public function shopiners()
     {
         return view('User.shopiners');
     }

    public function shop(Request $request)
    {
        $id_selected_categorie = $request->get('id_categorie') ?? null;
        $id_selected_sous_categorie = $request->get('selected_sous_categorie') ?? null;
        if ($id_selected_categorie) {
            $selected_categorie = categories::where('id', $id_selected_categorie)
                ->select('titre', 'id', 'luxury', 'small_icon', 'icon', 'title_en', 'title_ar')
                ->first();
        }
        if ($id_selected_sous_categorie) {
            $selected_sous_categorie = sous_categories::find($id_selected_sous_categorie);
        }

        $liste_categories = categories::where('active', true)
            ->orderBy('order')
            ->get(['titre', 'id', 'luxury', 'small_icon', 'icon', 'title_en', 'title_ar']);

        $key = $request->input('key') ?? null;

        $regions = regions::all();

        $luxury_only = $request->get('luxury_only');
        if (is_null($luxury_only)) {
            $luxury_only == 'false';
        } else {
            $luxury_only == 'true';
        }

        return view('User.shop')
            ->with('key', $key)
            ->with('luxury_only', $luxury_only)
            ->with('liste_categories', $liste_categories)
            ->with('selected_categorie', $selected_categorie ?? null)
            ->with('selected_sous_categorie', $selected_sous_categorie ?? null)
            ->with('regions', $regions);
    }

    public function get_user_categorie_post(Request $request)
    {
        $id_user = $request->input('id_user');
        $user = User::find($id_user);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Utilisateur introuvable!',
            ]);
        }

        $locale = App::getLocale();
        $categories = [];
        $posts = posts::where('id_user', $user->id)
            ->whereIn('statut', ['livré', 'vendu', 'livraison', 'préparation'])
            ->whereNotBlocked(Auth::id())
            ->get();

        foreach ($posts as $post) {
            $sous_categorie = sous_categories::find($post->id_sous_categorie);
            if ($sous_categorie) {
                $categorie = categories::find($sous_categorie->id_categorie);
                if ($categorie) {
                    // if (isset($categories[$categorie->titre])) {
                    //     $categories[$categorie->titre]++;
                    // } else {
                    //     $categories[$categorie->titre] = 1;
                    // }
                    $nom = match ($locale) {
                        'ar' => $categorie->title_ar,
                        'en' => $categorie->title_en,
                        default => $categorie->titre,
                    };

                    if (isset($categories[$nom])) {
                        $categories[$nom]++;
                    } else {
                        $categories[$nom] = 1;
                    }
                }
            }
        }

        // Convertir le tableau associatif en une liste pour la vue
        $categories_list = [];
        foreach ($categories as $nom => $count) {
            $categories_list[] = [
                'nom' => $nom,
                'count' => $count,
            ];
        }

        $ListeHtml = view('components.Liste-categories-vendus', ['categories' => $categories_list])->render();

        return response()->json([
            'status' => true,
            'html' => $ListeHtml,
            'username' => $user->username,
            'total' => count($categories),
        ]);
    }

    public function deletedNotice($id)
    {
        $post = posts::withTrashed()->findOrFail($id);
        // abort_unless($post->deleted_at || $post->motif_suppression, 404);
        return view('User.post-deleted', compact('post'));
    }
}
