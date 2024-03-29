<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\HomeController as ControllersHomeController;
use App\Http\Controllers\InformationsController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Security;
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


Route::get('/', [ControllersHomeController::class, 'index'])->name('home');
Route::get('/verify/{id_user}/{token}', [Security::class, 'verify_account']);
Route::get('/reset/{token}', [Security::class, 'reset_password']);
Route::get('/user/{id}', [ControllersHomeController::class, 'user_profile']);
Route::get('/shop', [ControllersHomeController::class, 'shop'])->name('shop');
Route::get('/about', [ControllersHomeController::class, 'about'])->name('about');
Route::get('/how_buy', [ControllersHomeController::class, 'how_buy'])->name('how_buy');
Route::get('/how_sell', [ControllersHomeController::class, 'how_sell'])->name('how_sell');
Route::get('/contact', [ControllersHomeController::class, 'contact'])->name('contact');
Route::get('/shopiners', [ControllersHomeController::class, 'shopiners'])->name('shopiners');


Route::get('/conditions', function () {
    return view('User.conditions');
})->name('conditions');

Route::get('/inscription', function () {
    return view('User.Auth-user.inscription');
})->name('inscription');
Route::get('/connexion', function () {
    return view('User.Auth-user.connexion');
})->name('connexion');

Route::get('/forget', function () {
    return view('User.Auth-user.forget');
})->name('forget');


Route::get('/post/{id}', [ControllersHomeController::class, 'details_post']);
Route::get('/post/{id}/{titre}', [ControllersHomeController::class, 'details_post']);



Route::group(['middleware' => 'auth'], function () {

    Route::get('/dashboard', [AdminController::class, 'show_admin_dashboard'])->name('dashboard');
    Route::get('/checkout', [ControllersHomeController::class, 'checkout'])->name('checkout');

    Route::get('/admin/categorie', function () {
        return view('Admin.categories.index');
    })->name('gestion_categorie');

    Route::get('/admin/proprietes', function () {
        return view('Admin.categories.index_proprietes');
    })->name('gestion_proprietes');

    Route::get('/informations', function () {
        return view('User.infromations');
    })->name('mes_informations');

    Route::get('/user-notifications', function () {
        return view('User.notifications');
    })->name('user-notifications');

    Route::get('/admin/changer_ordre_categorie', [CategoriesController::class, 'changerOrdre']);
    Route::get('/admin/changer_ordre_sous_categorie', [CategoriesController::class, 'changerOrdresous']);
    Route::get('/admin/changer_ordre_proprietes', [CategoriesController::class, 'changerOrdrepropriete']);
    Route::get('/admin/changer_ordre_propriete_in_sous_categorie', [CategoriesController::class, 'changer_ordre_propriete_in_sous_categorie']);
    Route::get('/admin/add_sous_categorie/{id}', [AdminController::class, 'add_sous_categorie'])->name('add_sous_categorie');
    Route::get('/admin/utilisateurs', [UserController::class, 'liste_utilisateurs'])->name('liste_utilisateurs');
    Route::get('/admin/publications', [PostsController::class, 'liste_publications'])->name('liste_publications');
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
});





Route::middleware('auth')->group(function () {

    Route::get('/historique', [ControllersHomeController::class, 'historiques'])->name('historique');
    Route::get('/mes-publication', [ControllersHomeController::class, 'index_mes_post'])->name('mes-publication');
    Route::get('/mes-achats', [ControllersHomeController::class, 'index_mes_achats'])->name('mes-achats');
    Route::get('/publication', [ControllersHomeController::class, 'index_post'])->name('publication');
    Route::get('/publication/{id}/update', [ControllersHomeController::class, 'index_post'])->name('udapte_publication');
    Route::get('/publication/{id_post}/propositions', [ControllersHomeController::class, 'list_proposition'])->name('list_propositions_publication');



    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});







require __DIR__ . '/auth.php';
