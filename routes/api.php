<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoriesController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\API\UsersController;
use App\Http\Controllers\API\shopinerController;
use App\Http\Controllers\API\ShopController;
use App\Http\Controllers\API\PostsController as postController;
use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\RatingController;
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
Route::post('/request-password-reset', [AuthController::class, 'requestPasswordReset'])->name('request-password-reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verifyOtp');
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
Route::post('/resend-email-verification', [AuthController::class, 'resendEmailVerification']);

Route::get('/regions', [ShopController::class, 'regions']);
Route::get('/categories', [CategoriesController::class, 'list_categorie'])->name('list_categorie');
Route::get('/categorie/{id}', [CategoriesController::class, 'details_categorie'])->name('details_categorie');
Route::get('/sub-categories', [CategoriesController::class, 'list_sub_categorie'])->name('list_sub_categorie');
Route::get('/sub-categories/{id}', [CategoriesController::class, 'details_sub_categorie'])->name('details_sub_categorie');
Route::get('/proprietes', [CategoriesController::class, 'list_proprietes'])->name('list_proprietes');
Route::get('/proprietes/{id}', [CategoriesController::class, 'details_proprietes'])->name('details_proprietes');
Route::get('/posts', [PostsController::class, 'list_post'])->name('list_post');
Route::get('/post/{id}', [PostsController::class, 'details_post'])->name('details_post');
Route::get('/banners', [CategoriesController::class, 'banners'])->name('banners');

Route::get('/shopiners', [shopinerController::class, 'getShopiners']);
Route::get('/shopiner/profile/{id}', [shopinerController::class, 'getShopinerProfile']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    //USERS
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::delete('/users/{id}', [AuthController::class, 'delete']);

    Route::post('/user/update', [UsersController::class, 'update']);

    //Posts
    Route::post('/favorites/toggle', [postController::class, 'toggleFavorite']);
    Route::get('/favorites/count', [postController::class, 'countFavorites']);
    Route::get('/favorites', [postController::class, 'listFavorites']);
    Route::get('/my-posts', [postController::class, 'MyPosts']);
    Route::get('/my-purchases', [postController::class, 'MesAchats']);
    Route::post('/posts/create', [postController::class, 'store']);
    Route::post('/posts/update/{id}', [postController::class, 'update']);
    Route::post('/posts/{id}/reduce-price', [postController::class, 'reducePrice']);
    Route::post('/posts/{id}/report', [postController::class, 'report']);

    //Cart
    Route::post('/add/panier', [ShopController::class, 'toggle_panier']);
    Route::get('/cart', [ShopController::class, 'index']);
    Route::delete('/cart/{id}', [ShopController::class, 'delete']);
    Route::delete('/cart', [ShopController::class, 'clear']);
    Route::post('/order/confirm', [ShopController::class, 'confirm']);
    Route::get('/orders', [ShopController::class, 'listOrders']);
    Route::get('/orders/{id}/track', [ShopController::class, 'track']);

    //Addresses
    Route::get('/addresses', [AddressController::class, 'index']);
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::post('/addresses/main', [AddressController::class, 'updateMainAddress']);
    Route::post('/addresses/{id}', [AddressController::class, 'update']);
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy']);
    Route::post('/addresses/{id}/set-default', [AddressController::class, 'setDefault']);
    Route::post('/addresses/{id}/unset-default', [AddressController::class, 'unsetDefault']);


    Route::get('/users/rating/purchases', [RatingController::class, 'show']);
    Route::post('/users/{purchase_id}/rating', [RatingController::class, 'store']);


    Route::post('/users/{user}/ping', [shopinerController::class, 'ping']);
    Route::delete('/users/{user}/ping', [shopinerController::class, 'unping']);

});

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('/notifications/list', [NotificationsController::class, 'list_notification'])->name('list_notification');
    Route::get('/notifications/as_read/{id}', [NotificationsController::class, 'mark_as_read_notification'])->name('mark_as_read_notification');
    Route::get('/notifications/delete/{id}', [NotificationsController::class, 'delete_notification'])->name('delete_notification');
});



Route::post('/check_username', [PostsController::class, 'username'])->name('username');


Route::post('/reset_password', [AuthController::class, 'reset_password']);
Route::post('/mail/delete', [AuthController::class, 'delete_email']);
