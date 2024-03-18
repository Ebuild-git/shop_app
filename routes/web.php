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
Route::get('/checkout', [ControllersHomeController::class, 'checkout'])->name('checkout');




Route::get('/conditions', function () {
    return view('User.conditions');
})->name('conditions');
Route::get('/faqs', function () {
    return view('User.faq');
})->name('faqs');
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



Route::group(['middleware' => 'auth'], function () {

    Route::get('/dashboard', [AdminController::class, 'show_admin_dashboard'])->name('dashboard');

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
    Route::get('/admin/add_sous_categorie/{id}', [AdminController::class, 'add_sous_categorie'])->name('add_sous_categorie');
    Route::get('/admin/utilisateurs', [UserController::class, 'liste_utilisateurs'])->name('liste_utilisateurs');
    Route::get('/admin/publications', [PostsController::class, 'liste_publications'])->name('liste_publications');
    Route::get('/admin/informations', [InformationsController::class, 'index'])->name('informations');
    Route::get('/admin/client/{id}/view', [UserController::class, 'details_user'])->name('vue_details_utilisateurs');
    Route::get('/admin/publication/{id}/view', [PostsController::class, 'details_publication'])->name('vue_details_publication');
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
