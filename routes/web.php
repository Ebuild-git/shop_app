<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\InformationsController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return view('welcome');
});


Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', function () {
        return view('Admin.dashboard');
    })->name('dashboard');
    Route::get('/admin/categorie', function () {
        return view('Admin.categories.index');
    })->name('gestion_categorie');
    Route::get('/admin/utilisateurs', [UserController::class, 'liste_utilisateurs'])->name('liste_utilisateurs');
    Route::get('/admin/publications', [PostsController::class, 'liste_publications'])->name('liste_publications');
    Route::get('/admin/informations', [InformationsController::class, 'index'])->name('informations');
    Route::get('/admin/client/{id}/view', [UserController::class, 'details_user'])->name('vue_details_utilisateurs');
    Route::get('/admin/publication/{id}/view', [PostsController::class, 'details_publication'])->name('vue_details_publication');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});







require __DIR__ . '/auth.php';
