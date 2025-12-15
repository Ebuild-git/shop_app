<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, posts};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class shopinerController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/shopiners",
     *     tags={"Shopiners"},
     *     summary="List paginated shopiners",
     *     description="Returns a paginated list of shopiners with full avatar URLs",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="key",
     *         in="query",
     *         description="Search by username",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="rating",
     *         in="query",
     *         description="Filter users with average rating greater than or equal to this value",
     *         required=false,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of shopiners",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="data", type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=11),
     *                         @OA\Property(property="firstname", type="string", example="John"),
     *                         @OA\Property(property="lastname", type="string", example="Doe"),
     *                         @OA\Property(property="username", type="string", example="johndoe"),
     *                         @OA\Property(property="voyage_mode", type="string", example="Fast"),
     *                         @OA\Property(property="avatar", type="string", example="http://127.0.0.1:8000/storage/uploads/avatars/avatar1.png"),
     *                         @OA\Property(property="photo_verified_at", type="string", nullable=true, example="2025-01-10 12:00:00"),
     *                         @OA\Property(property="average_rating", type="number", format="float", example=4.5),
     *                         @OA\Property(property="total_posts", type="integer", example=23),
     *                         @OA\Property(property="total_reviews", type="integer", example=10)
     *                     )
     *                 ),
     *                 @OA\Property(property="total", type="integer", example=150),
     *                 @OA\Property(property="per_page", type="integer", example=50),
     *                 @OA\Property(property="last_page", type="integer", example=3)
     *             )
     *         )
     *     )
     * )
     */
    public function getShopiners(Request $request)
    {
        $key = $request->input('key');
        $rating = $request->input('rating');
        $userId = auth()->id();

        $Query = User::select(
                'users.id',
                'users.firstname',
                'users.lastname',
                'users.username',
                'users.voyage_mode',
                'users.avatar',
                'users.photo_verified_at',
                DB::raw('AVG(ratings.etoiles) as average_rating'),
                DB::raw('COUNT(posts.id) as total_posts'),
                DB::raw('COUNT(ratings.id) as total_reviews')
            )
            ->leftJoin('ratings', 'users.id', '=', 'ratings.id_user_sell')
            ->leftJoin('posts', 'users.id', '=', 'posts.id_user')
            ->leftJoin('pings', function ($join) use ($userId) {
                $join->on('users.id', '=', 'pings.pined')
                    ->where('pings.id_user', $userId);
            })
            ->where('users.role', '!=', 'admin')
            ->where('users.locked', false);

        if ($key) {
            $Query->where('users.username', 'LIKE', $key . '%');
        }

        if ($rating) {
            $Query->having('average_rating', '>=', $rating);
        }

        $shopiners = $Query->groupBy(
                'users.id',
                'users.firstname',
                'users.lastname',
                'users.username',
                'users.voyage_mode',
                'users.avatar',
                'users.photo_verified_at',
                'pings.id_user'
            )
            ->orderByRaw('CASE WHEN pings.id_user IS NOT NULL THEN 0 ELSE 1 END')
            ->orderByDesc('total_reviews')
            ->orderBy('users.username')
            ->orderByDesc('average_rating')
            ->orderBy('total_posts')
            ->paginate(50);

        $shopiners->getCollection()->transform(function ($user) {
            $user->avatar = $user->avatar ? asset('storage/' . $user->avatar) : null;
            return $user;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'current_page' => $shopiners->currentPage(),
                'data' => $shopiners->items(),
                'total' => $shopiners->total(),
                'per_page' => $shopiners->perPage(),
                'last_page' => $shopiners->lastPage(),
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/shopiner/profile/{id}",
     *     tags={"Shopiners"},
     *     summary="Get Shopiner profile",
     *     description="Returns a Shopiner's profile including average rating, total posts, total reviews, total views, and posts created by him",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Shopiner ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Shopiner profile retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="shopiner", type="object",
     *                     @OA\Property(property="id", type="integer", example=11),
     *                     @OA\Property(property="firstname", type="string", example="Malak"),
     *                     @OA\Property(property="lastname", type="string", example="Salame"),
     *                     @OA\Property(property="username", type="string", example="Louka"),
     *                     @OA\Property(property="voyage_mode", type="integer", example=0),
     *                     @OA\Property(property="avatar", type="string", example="http://127.0.0.1:8000/storage/avatar.png"),
     *                     @OA\Property(property="photo_verified_at", type="string", nullable=true, example="2024-10-02 23:11:45"),
     *                     @OA\Property(property="average_rating", type="string", example="3.0000"),
     *                     @OA\Property(property="total_posts", type="integer", example=18),
     *                     @OA\Property(property="total_reviews", type="integer", example=18),
     *                     @OA\Property(property="total_views", type="integer", example=120)
     *                 ),
     *                 @OA\Property(property="posts", type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=30),
     *                         @OA\Property(property="titre", type="string", example="Manteau old style"),
     *                         @OA\Property(property="description", type="string", example="Manteau camel Mango"),
     *                         @OA\Property(property="photos", type="array",
     *                             @OA\Items(type="string", example="http://127.0.0.1:8000/storage/uploads/posts/TqhzM91f7GYgu5V6NwiYYNi1hKZEk9TlA26dZJ8a.webp")
     *                         ),
     *                         @OA\Property(property="prix", type="string", example="300.00"),
     *                         @OA\Property(property="etat", type="string", example="Bon état")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Shopiner not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Shopiner not found")
     *         )
     *     )
     * )
     */
    public function getShopinerProfile($id)
    {
        $shopiner = User::select(
                'users.id',
                'users.firstname',
                'users.lastname',
                'users.username',
                'users.email',
                'users.phone_number',
                'users.address',
                'users.region',
                'users.birthdate',
                'users.locked',
                'users.rue',
                'users.nom_batiment',
                'users.etage',
                'users.num_appartement',
                'users.voyage_mode',
                'users.avatar',
                'users.photo_verified_at',
                DB::raw('AVG(ratings.etoiles) as average_rating'),
                DB::raw('COUNT(posts.id) as total_posts'),
                DB::raw('COUNT(ratings.id) as total_reviews'),
                DB::raw('SUM(posts.views) as total_views')
            )
            ->leftJoin('ratings', 'users.id', '=', 'ratings.id_user_sell')
            ->leftJoin('posts', 'users.id', '=', 'posts.id_user')
            ->where('users.id', $id)
            ->groupBy(
                'users.id',
                'users.firstname',
                'users.lastname',
                'users.username',
                'users.email',
                'users.phone_number',
                'users.address',
                'users.region',
                'users.birthdate',
                'users.locked',
                'users.rue',
                'users.nom_batiment',
                'users.etage',
                'users.num_appartement',
                'users.voyage_mode',
                'users.avatar',
                'users.photo_verified_at'
            )
            ->first();

        if (!$shopiner) {
            return response()->json([
                'success' => false,
                'message' => 'Shopiner not found'
            ], 404);
        }

        $shopiner->avatar = $shopiner->avatar ? asset('storage/' . $shopiner->avatar) : null;

        $posts = posts::where('id_user', $id)->get();
        $posts->transform(function ($post) {
            $post->photos = collect($post->photos)->map(fn($photo) => asset('storage/' . $photo));
            return $post;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'shopiner' => $shopiner,
                'posts' => $posts
            ]
        ]);
    }


}
