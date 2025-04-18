<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\FavorisController;
use App\Http\Controllers\HomeController as ControllersHomeController;
use App\Http\Controllers\InformationsController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\PartenairesController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Security;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SignalementsController;
use App\Http\Controllers\test;
use App\Http\Controllers\UserController;
use App\Models\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


//lang

Route::get('/change-lang/{lang}', function ($lang) {
    if (in_array($lang, ['fr', 'ar', 'en'])) {
        session(['locale' => $lang]);
    }
    return redirect()->back();
})->name('change.lang');

//gestion de la connexion admin
Route::get('login', [AdminController::class, 'index_login'])->name('login');
Route::get('logout', [AdminController::class, 'index_logout'])->name('logout');
Route::post('login.post', [AdminController::class, 'post_login'])->name('login.post');

Route::get('/', [ControllersHomeController::class, 'index'])->name('home');
Route::get('/verify/{id_user}/{token}', [Security::class, 'verify_account'])->name('verify_account');
Route::get('/reset/{token}', [Security::class, 'reset_password'])->name('reset_password');
Route::get('/shop', [ControllersHomeController::class, 'shop'])->name('shop');
Route::get('/about', [ControllersHomeController::class, 'about'])->name('about');
Route::get('/how_buy', [ControllersHomeController::class, 'how_buy'])->name('how_buy');
Route::get('/how_sell', [ControllersHomeController::class, 'how_sell'])->name('how_sell');
Route::get('/contact', [ControllersHomeController::class, 'contact'])->name('contact');
Route::get('/conditions', [ControllersHomeController::class, 'conditions'])->name('conditions');
Route::get('/inscription', [ControllersHomeController::class, 'inscription'])->name('inscription');
Route::post('/inscription', [ControllersHomeController::class, 'inscription_post'])->name('inscription.post');
Route::get('/connexion', [ControllersHomeController::class, 'connexion'])->name('connexion');
Route::get('/forget', [ControllersHomeController::class, 'forget'])->name('forget');
Route::get('/post/{id}', [ControllersHomeController::class, 'details_post'])->name('details_post_single');
Route::get('/post/{id}/{titre}', [ControllersHomeController::class, 'details_post'])->name('details_post2');
Route::get('/color-name', [ColorController::class, 'getColorName']);


Route::post('/recherche', [ShopController::class, 'index'])->name('recherche');

//panier
Route::get('/remove_to_card', [ControllersHomeController::class, 'remove_to_card'])->name('remove_to_card');
Route::get('/count_panier', [ControllersHomeController::class, 'count_panier'])->name('count_panier');
Route::get('/like', [ControllersHomeController::class, 'like'])->name('like');



Route::group(['middleware' => ['auth', 'loggedOut']], function () {

    Route::get('/user/{id}', [ControllersHomeController::class, 'user_profile'])->name('user_profile');
    Route::get('/add_panier', [ControllersHomeController::class, 'add_panier'])->name('add_panier');


    Route::get('/shopiners', [ControllersHomeController::class, 'shopiners'])->name('shopiners');
    Route::get('/historique/{type}', [ControllersHomeController::class, 'historiques'])->name('historique');


    Route::get('/mes-publication', [ControllersHomeController::class, 'index_mes_post'])->name('mes-publication');
    Route::get('/mes-achats', [ControllersHomeController::class, 'index_mes_achats'])->name('mes-achats');


    Route::get('/publication', [ControllersHomeController::class, 'index_post'])->name('publication');

    Route::get('/rib', [ControllersHomeController::class, 'showRibForm'])->name('rib.form');
    Route::post('/rib/submit', [ControllersHomeController::class, 'submitRib'])->name('rib.submit');
    //gestion des notifications
    Route::get('/user-notifications', [NotificationsController::class, 'user_notifications'])->name('user-notifications');
    Route::get('/delete_notification', [NotificationsController::class, 'delete_notification']);
    Route::get('/count_notification', [NotificationsController::class, 'count_notification']);
    Route::get('/delete/all_notifications', [NotificationsController::class, 'delete_all']);

    // gestion des like des posts
    Route::get('/liked', [LikesController::class, 'index'])->name('liked');
    Route::get('/remove_liked', [LikesController::class, 'remove_liked'])->name('remove_liked');
    Route::get('/like_post', [LikesController::class, 'like_post'])->name('like_post');

    //gestion des posts
    Route::get('/list_motifs', [PostsController::class, 'list_motifs'])->name('list_motifs');
    Route::post('/delete_my_post', [UserController::class, 'delete_my_post'])->name('delete_my_post');

    //gestion des favoris
    Route::get('/favoris', [FavorisController::class, 'index'])->name('favoris');
    Route::get('/remove_favoris', [FavorisController::class, 'remove_favoris'])->name('remove_favoris');
    Route::get('/ajouter_favoris', [FavorisController::class, 'ajouter_favoris'])->name('ajouter_favoris');


    Route::get('/informations', [ControllersHomeController::class, 'informations'])->name('mes_informations');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/checkout', [ControllersHomeController::class, 'checkout'])->name('checkout');
    Route::get('/category/post_user', [ControllersHomeController::class, 'get_user_categorie_post'])->name('get_user_categorie_post');
});




Route::group(['middleware' => ['auth', 'role']], function () {



    Route::get('/dashboard', [AdminController::class, 'show_admin_dashboard'])->name('dashboard');


    Route::get('/admin/categorie', [AdminController::class, 'index_categories'])->name('gestion_categorie');
    Route::get('/admin/proprietes', [AdminController::class, 'index_proprietes'])->name('gestion_proprietes');

    Route::get('/admin/changer_ordre_categorie', [CategoriesController::class, 'changerOrdre']);
    Route::get('/admin/changer_ordre_sous_categorie', [CategoriesController::class, 'changerOrdresous']);
    Route::get('/admin/changer_ordre_proprietes', [CategoriesController::class, 'changerOrdrepropriete']);
    Route::get('/admin/changer_ordre_propriete_in_sous_categorie', [CategoriesController::class, 'changer_ordre_propriete_in_sous_categorie']);
    Route::get('/admin/add_sous_categorie/{id}', [AdminController::class, 'add_sous_categorie'])->name('add_sous_categorie');
    Route::get('/admin/utilisateurs', [UserController::class, 'liste_utilisateurs'])->name('liste_utilisateurs');
    Route::get('/admin/utilisateurs/locked', [UserController::class, 'liste_utilisateurs_locked'])->name('liste_utilisateurs_locked');

    Route::get('/admin/publications', [PostsController::class, 'liste_publications'])->name('liste_publications');
    Route::get('/admin/publications/deleted', [PostsController::class, 'liste_publications_supprimer'])->name('liste_publications_supprimer');
    Route::get('/admin/informations', [InformationsController::class, 'index'])->name('informations');
    Route::get('/admin/client/{id}/view', [UserController::class, 'details_user'])->name('vue_details_utilisateurs');
    Route::get('/admin/publication/{id}/view', [PostsController::class, 'details_publication'])->name('vue_details_publication');
    Route::get('/admin/add_categorie', [CategoriesController::class, 'add_categorie'])->name('add_categorie');
    Route::get('/admin/update_categorie/{id}', [CategoriesController::class, 'update_categorie'])->name('admin_update_categorie');
    Route::get('/admin/ajouter/regions', [CategoriesController::class, 'add_regions'])->name('add_regions');
    Route::get('/admin/update_sous_categorie/{id}', [CategoriesController::class, 'update_sous_categorie'])->name('update_sous_categorie');
    Route::post('/admin/update_sous_categorie', [CategoriesController::class, 'post_update_sous_categorie'])->name('post.update_sous_categorie');
    Route::get('/admin/grille_prix', [CategoriesController::class, 'grille_prix'])->name('grille_prix');
    Route::get('/admin/settings', [AdminController::class, 'admin_settings'])->name('admin_settings');
    Route::get('/admin/settings_security', [AdminController::class, 'admin_settings_security'])->name('admin_settings_security');

    Route::get('/admin/update_propriete/{id}', [AdminController::class, 'update_propriete'])->name('update_propriete');
    Route::get('/admin/export-user', [AdminController::class, 'export_users'])->name('export_users');


    Route::get('/admin/changer_ordre_attribus', [CategoriesController::class, 'changer_ordre_attribus'])->name('changer_ordre_attribus');


    Route::post('/admin/change_picture_statut', [InformationsController::class, 'change_picture_statut'])->name('change_picture_statut');
    Route::post('/admin/update_information_website', [InformationsController::class, 'update_information_website'])->name('update_information_website');



    //gestion des signalememnts
    Route::get('/admin/post/{post_id}/signalement', [SignalementsController::class, 'liste_signalement_publications'])->name('liste_signalement_publications');
    Route::post('/admin/signalement/filtre', [SignalementsController::class, 'liste_publications_signaler'])->name('filtre_signalement');
    Route::post('/admin/signalement/delete/{id}', [SignalementsController::class, 'delete'])->name('delete_signalement');
    Route::get('/admin/publications/signalements', [SignalementsController::class, 'liste_publications_signaler'])->name('post_signalers');
    Route::get('/admin/user/{user_id}/signalement', [SignalementsController::class, 'liste_signalement_by_user'])->name('liste_signalement_by_user');


    //gestion de nos partenaire
    Route::get('/admin/nos_partenaires', [PartenairesController::class, 'index'])->name('nos_partenaires');
    Route::post('/admin/nos_partenaires/create', [PartenairesController::class, 'create'])->name('create_partenaires');
    Route::post('/admin/nos_partenaires/delete', [PartenairesController::class, 'delete'])->name('delete_partenaires');

    //SHIPEMENT

    Route::get('/admin/shipment', [AdminController::class, 'shipment'])->name('shipment');
    Route::post('/admin/client/{id}/validate-photo', [UserController::class, 'validatePhoto'])->name('admin.validate.photo');

});














//require __DIR__ . '/auth.php';
