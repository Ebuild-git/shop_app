<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoriesController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\shopinerController;
use App\Http\Controllers\API\ShopController;
use App\Models\User;
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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-code', [AuthController::class, 'verifyCode']);
Route::get('/regions', [ShopController::class, 'regions']);
Route::get('/categories', [CategoriesController::class, 'list_categorie'])->name('list_categorie');
Route::get('/categorie/{id}', [CategoriesController::class, 'details_categorie'])->name('details_categorie');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

});

Route::group(['middleware' => 'jwt.auth'], function () {
    // Vos routes protégées

    //posts
    Route::post('/posts/create', [PostsController::class, 'create_post'])->name('create_post');
    Route::post('/posts/update', [PostsController::class, 'update_post'])->name('update_post');

    //users
    Route::post('/user/update/securty', [AuthController::class, 'update_password']);
    Route::post('/user/update', [AuthController::class, 'update_information']);


    //notifications
    Route::get('/notifications/list', [NotificationsController::class, 'list_notification'])->name('list_notification');
    Route::get('/notifications/as_read/{id}', [NotificationsController::class, 'mark_as_read_notification'])->name('mark_as_read_notification');
    Route::get('/notifications/delete/{id}', [NotificationsController::class, 'delete_notification'])->name('delete_notification');

    //shopiners
    Route::get('/shopiners', [shopinerController::class, 'getShopiners']);

});



Route::get('/posts', [PostsController::class, 'list_post'])->name('list_post');
Route::get('/post/{id}', [PostsController::class, 'details_post'])->name('details_post');

Route::post('/check_username', [PostsController::class, 'username'])->name('username');


Route::post('/reset_password', [AuthController::class, 'reset_password']);
Route::post('/mail/delete', [AuthController::class, 'delete_email']);
