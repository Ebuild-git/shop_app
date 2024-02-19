<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\PostsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//posts
Route::get('/posts/list', [PostsController::class, 'list_post'])->name('list_post');
Route::get('/posts/details/{id}', [PostsController::class, 'details_post'])->name('details_post');
Route::post('/posts/create', [PostsController::class, 'create_post'])->name('create_post');
Route::post('/posts/update', [PostsController::class, 'update_post'])->name('update_post');


Route::group(['middleware' => 'jwt.auth'], function () {
    // Vos routes protégées
});


//categories
Route::get('/categories/list', [CategoriesController::class, 'list_categorie'])->name('list_categorie');
Route::get('/categories/details/{id}', [CategoriesController::class, 'details_categorie'])->name('details_categorie');
Route::get('/categories/delete/{id}', [CategoriesController::class, 'delete_categorie'])->name('delete_categorie');
Route::post('/categories/create', [CategoriesController::class, 'create_categorie'])->name('create_categorie');
Route::post('/categories/update', [CategoriesController::class, 'update_categorie'])->name('update_categorie');

//notifications
Route::get('/notifications/list', [NotificationsController::class, 'list_notification'])->name('list_notification');
Route::get('/notifications/as_read/{id}', [NotificationsController::class, 'mark_as_read_notification'])->name('mark_as_read_notification');
Route::get('/notifications/delete/{id}', [NotificationsController::class, 'delete_notification'])->name('delete_notification');