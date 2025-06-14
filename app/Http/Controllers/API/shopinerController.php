<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class shopinerController extends Controller
{
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

        return response()->json($shopiners);
    }
}
